<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Ticket;
use App\Models\TicketCode;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard overview page.
     */
    public function dashboard()
    {
        $stats = [
            'total_transactions'   => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'success_transactions' => Transaction::where('status', 'success')->count(),
            'expired_transactions' => Transaction::where('status', 'expired')->count(),
            'total_revenue'        => Transaction::where('status', 'success')->sum('base_price'),
            'tickets_sold'         => Transaction::where('status', 'success')->sum('quantity'),
            'scanned_today'        => TicketCode::where('is_scanned', true)
                                        ->whereDate('scanned_at', today())
                                        ->count(),
            'qr_generated'         => TicketCode::count(),
            'qr_scanned'           => TicketCode::where('is_scanned', true)->count(),
        ];

        $tickets       = Ticket::all();
        $recentOrders  = Transaction::with('ticket')
                            ->latest()
                            ->limit(10)
                            ->get();

        return view('admin.dashboard', compact('stats', 'tickets', 'recentOrders'));
    }

    /**
     * List all transactions with optional filtering.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with('ticket')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Manually confirm (approve) a pending transaction.
     * In production this would be triggered by a Moota webhook.
     */
    public function confirmTransaction(string $invoice)
    {
        $transaction = Transaction::with('ticket')
            ->where('invoice_number', $invoice)
            ->where('status', 'pending')
            ->firstOrFail();

        \DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'success']);

            // Generate one TicketCode per quantity purchased
            for ($i = 0; $i < $transaction->quantity; $i++) {
                \App\Models\TicketCode::create([
                    'transaction_id'     => $transaction->id,
                    'unique_ticket_code' => $transaction->generateTicketCode($i),
                    'is_scanned'         => false,
                    'scanned_at'         => null,
                ]);
            }
        });

        // Dispatch job to generate PDF and send email
        \App\Jobs\ProcessTicketPurchase::dispatch($transaction);

        return back()->with('success', "Transaksi {$invoice} berhasil dikonfirmasi. Tiket telah di-generate.");
    }

    /**
     * Mark a pending transaction as expired (manual action / scheduled job trigger).
     */
    public function expireTransaction(string $invoice)
    {
        $transaction = Transaction::where('invoice_number', $invoice)
            ->where('status', 'pending')
            ->firstOrFail();

        \DB::transaction(function () use ($transaction) {
            // Restore quota back to the ticket
            $transaction->ticket()->increment('remaining_quota', $transaction->quantity);
            $transaction->update(['status' => 'expired']);
        });

        return back()->with('success', "Transaksi {$invoice} telah ditandai expired. Kuota dikembalikan.");
    }

    /**
     * Merchandise management index.
     */
    public function merchandises()
    {
        $merchandises = Merchandise::latest()->paginate(12);

        return view('admin.merchandises', compact('merchandises'));
    }

    public function merchandiseStore(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->saveWebP($request->file('image'), 'merch');
        }
        unset($data['stock']); // stock not in table, ignore

        Merchandise::create($data);

        return back()->with('success', 'Merchandise berhasil ditambahkan.');
    }

    public function merchandiseUpdate(Request $request, Merchandise $merchandise)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($merchandise->image) \Storage::disk('public')->delete($merchandise->image);
            $data['image'] = $this->saveWebP($request->file('image'), 'merch');
        } else {
            unset($data['image']);
        }

        $merchandise->update($data);

        return back()->with('success', 'Merchandise berhasil diperbarui.');
    }

    public function merchandiseDestroy(Merchandise $merchandise)
    {
        $merchandise->delete();

        return back()->with('success', 'Merchandise berhasil dihapus.');
    }

    // -----------------------------------------------------------------------
    // PRIVATE HELPERS
    // -----------------------------------------------------------------------

    public function scanQr(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $ticketCode = TicketCode::where('unique_ticket_code', $request->code)->first();

        if (!$ticketCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau bukan dari aplikasi ini!'
            ], 404);
        }

        if ($ticketCode->is_scanned) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah di-scan pada ' . $ticketCode->scanned_at->format('d M Y H:i')
            ], 400);
        }

        $ticketCode->update([
            'is_scanned' => true,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil di-scan! (Invoice: ' . $ticketCode->transaction->invoice_number . ')'
        ]);
    }

    private function saveWebP($file, string $folder): string
    {
        $ext   = strtolower($file->getClientOriginalExtension());
        $name  = time() . '_' . uniqid() . '.webp';
        $dest  = $folder . '/' . $name;

        if ($ext === 'webp') {
            $file->storeAs($folder, $name, 'public');
            return $dest;
        }

        $image = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png'         => (function ($f) {
                $img = imagecreatefrompng($f->getRealPath());
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                return $img;
            })($file),
            default       => null,
        };

        if (!$image) {
            $file->storeAs($folder, $name, 'public');
            return $dest;
        }

        ob_start();
        imagewebp($image, null, 82);
        $bytes = ob_get_clean();
        imagedestroy($image);

        \Storage::disk('public')->put($dest, $bytes);
        return $dest;
    }
}
