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

    // -----------------------------------------------------------------------
    // PRIVATE HELPERS
    // -----------------------------------------------------------------------
}
