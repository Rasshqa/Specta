<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Ticket;
use App\Models\TicketCode;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Admin dashboard overview page.
     */
    public function dashboard()
    {
        // One query for all transaction counts/sums instead of 5 separate queries
        $stats = cache()->remember('admin_dashboard_stats', 60, function () {
            $agg = Transaction::selectRaw("
                COUNT(*) AS total_transactions,
                SUM(CASE WHEN status = 'PENDING_PROOF' THEN 1 ELSE 0 END) AS pending_transactions,
                SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) AS success_transactions,
                SUM(CASE WHEN status = 'REJECTED' THEN 1 ELSE 0 END) AS expired_transactions,
                SUM(CASE WHEN status = 'SUCCESS' THEN total_price ELSE 0 END) AS total_revenue,
                SUM(CASE WHEN status = 'SUCCESS' THEN quantity ELSE 0 END) AS tickets_sold
            ")->first();

            $ticketAgg = TicketCode::selectRaw("
                COUNT(*) AS qr_generated,
                SUM(CASE WHEN is_scanned = 1 THEN 1 ELSE 0 END) AS qr_scanned,
                SUM(CASE WHEN is_scanned = 1 AND DATE(scanned_at) = CURDATE() THEN 1 ELSE 0 END) AS scanned_today
            ")->first();

            return [
                'total_transactions'   => (int) $agg->total_transactions,
                'pending_transactions' => (int) $agg->pending_transactions,
                'success_transactions' => (int) $agg->success_transactions,
                'expired_transactions' => (int) $agg->expired_transactions,
                'total_revenue'        => (float) $agg->total_revenue,
                'tickets_sold'         => (int) $agg->tickets_sold,
                'scanned_today'        => (int) $ticketAgg->scanned_today,
                'qr_generated'         => (int) $ticketAgg->qr_generated,
                'qr_scanned'           => (int) $ticketAgg->qr_scanned,
            ];
        });

        // Cache ticket data (rarely changes)
        $tickets = cache()->remember('admin_tickets_list', 3600, function () {
            return Ticket::select(['id', 'ticket_name', 'price', 'remaining_quota'])->get();
        });

        // Select only columns needed by the view, avoid SELECT *
        $recentOrders = Transaction::select([
                'id', 'invoice_number', 'buyer_name', 'buyer_email',
                'ticket_id', 'quantity', 'total_price', 'status', 'created_at'
            ])
            ->with(['ticket:id,ticket_name'])
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
        $query = Transaction::select([
                'id', 'invoice_number', 'buyer_name', 'buyer_email', 'buyer_whatsapp',
                'ticket_id', 'quantity', 'total_price', 'status', 'payment_proof', 'created_at'
            ])
            ->with(['ticket:id,ticket_name'])
            ->latest();

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
            if ($merchandise->image) Storage::disk('public')->delete($merchandise->image);
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

        $ticketCode = TicketCode::with(['transaction:id,invoice_number,buyer_name,status'])
            ->where('unique_ticket_code', $request->code)
            ->first();

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

        Storage::disk('public')->put($dest, $bytes);
        return $dest;
    }
}
