<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TicketCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TransactionApprovalController extends Controller
{
    /**
     * Approve a transaction.
     * - Switches status to 'SUCCESS'
     * - Auto-deletes the physical payment proof file
     * - Generates unique ticket codes for each purchased seat
     */
    public function approve(string $invoice)
    {
        $transaction = Transaction::query()->with('ticket')
            ->where('invoice_number', '=', $invoice)
            ->where('status', '=', 'PENDING_PROOF')
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {
            // 1. Mark as SUCCESS
            $transaction->update(['status' => 'SUCCESS']);

            // 2. Auto-delete the physical proof file
            if ($transaction->payment_proof) {
                $proofPath = 'proofs/' . $transaction->payment_proof;

                if (Storage::disk('public')->exists($proofPath)) {
                    Storage::disk('public')->delete($proofPath);
                } else {
                    $absolutePath = storage_path('app/public/' . $proofPath);
                    if (file_exists($absolutePath)) {
                        @unlink($absolutePath);
                    }
                }

                $transaction->update(['payment_proof' => null]);
            }

            // 3. Generate all codes first, then batch insert (avoids N+1 per-seat query)
            $codes = [];
            $now   = now();
            for ($i = 0; $i < $transaction->quantity; $i++) {
                // Generate a candidate code and retry on rare collision
                $attempts = 0;
                do {
                    $code   = 'TKT-' . strtoupper(Str::random(5)) . '-' . rand(10000, 99999) . '-' . ($i + 1);
                    $exists = in_array($code, array_column($codes, 'unique_ticket_code'))
                              || TicketCode::where('unique_ticket_code', '=', $code)->exists();
                    $attempts++;
                } while ($exists && $attempts < 5);

                $codes[] = [
                    'transaction_id'     => $transaction->id,
                    'unique_ticket_code' => $code,
                    'is_scanned'         => false,
                    'scanned_at'         => null,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ];
            }

            // Single INSERT for all ticket codes instead of one INSERT per seat
            TicketCode::insert($codes);
        });

        // Invalidate dashboard stats cache so numbers update immediately
        cache()->forget('admin_dashboard_stats');

        Log::info("Transaction approved: {$invoice} — {$transaction->quantity} ticket(s) generated.");

        return back()->with('success', "Transaksi {$invoice} disetujui. {$transaction->quantity} tiket berhasil di-generate dan bukti pembayaran telah dihapus.");
    }

    /**
     * Reject a transaction.
     * - Switches status to 'REJECTED'
     * - Restores quota back to the ticket pool
     */
    public function reject(string $invoice)
    {
        $transaction = Transaction::query()->where('invoice_number', '=', $invoice)
            ->where('status', '=', 'PENDING_PROOF')
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {
            // Restore quota back to the ticket using direct DB update (no extra SELECT)
            if ($transaction->ticket_id) {
                DB::table('tickets')
                    ->where('id', '=', $transaction->ticket_id)
                    ->increment('remaining_quota', $transaction->quantity);
            }

            // Delete proof file if exists (cleanup even on rejection)
            if ($transaction->payment_proof) {
                $proofPath = 'proofs/' . $transaction->payment_proof;
                if (Storage::disk('public')->exists($proofPath)) {
                    Storage::disk('public')->delete($proofPath);
                }
                $transaction->update(['payment_proof' => null]);
            }

            $transaction->update(['status' => 'REJECTED']);
        });

        // Invalidate dashboard stats cache
        cache()->forget('admin_dashboard_stats');
        cache()->forget('admin_tickets_list');
        cache()->forget('admin_tickets_list_api');

        Log::info("Transaction rejected: {$invoice} — quota restored.");

        return back()->with('success', "Transaksi {$invoice} ditolak. Kuota tiket dikembalikan.");
    }
}
