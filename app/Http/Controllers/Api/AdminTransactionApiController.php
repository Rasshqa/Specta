<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\TransactionApprovalController;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCode;
use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminTransactionApiController extends Controller
{
    use ApiResponser;

    private const TICKET_PRICE = 110000;

    /**
     * List all transactions awaiting payment proof review.
     */
    public function pending(): JsonResponse
    {
        try {
            $transactions = Transaction::query()->where('status', '=', 'PENDING_PROOF')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (Transaction $tx) => $this->formatTransaction($tx));

            return $this->successResponse(
                $transactions,
                'Pending transactions retrieved successfully'
            );
        } catch (\Exception $e) {
            Log::error('API Pending Transactions Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve pending transactions', 500);
        }
    }

    /**
     * Approve a pending transaction (delegates to web approval logic).
     */
    public function approve(string $invoice): JsonResponse
    {
        try {
            app(TransactionApprovalController::class)->approve($invoice);

            $transaction = Transaction::query()->where('invoice_number', '=', $invoice)->first();

            return $this->successResponse(
                $transaction ? $this->formatTransaction($transaction) : null,
                "Transaction {$invoice} approved successfully"
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Transaction not found or not pending', 404);
        } catch (\Exception $e) {
            Log::error("API Approve Error [{$invoice}]: " . $e->getMessage());
            return $this->errorResponse('Failed to approve transaction', 500);
        }
    }

    /**
     * Reject a pending transaction (delegates to web approval logic).
     */
    public function reject(string $invoice): JsonResponse
    {
        try {
            app(TransactionApprovalController::class)->reject($invoice);

            $transaction = Transaction::query()->where('invoice_number', '=', $invoice)->first();

            return $this->successResponse(
                $transaction ? $this->formatTransaction($transaction) : null,
                "Transaction {$invoice} rejected successfully"
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Transaction not found or not pending', 404);
        } catch (\Exception $e) {
            Log::error("API Reject Error [{$invoice}]: " . $e->getMessage());
            return $this->errorResponse('Failed to reject transaction', 500);
        }
    }

    /**
     * Create and auto-approve a manual admin ticket order.
     */
    public function storeManual(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated): Transaction {
                $ticket = Ticket::query()->where('ticket_name', '=', 'Tiket Reguler')->lockForUpdate()->first()
                    ?? Ticket::query()->lockForUpdate()->first();

                if (!$ticket) {
                    throw new \RuntimeException('No active ticket type available.');
                }

                if (!$ticket->hasAvailableQuota($validated['quantity'])) {
                    throw new \RuntimeException(
                        "Insufficient quota. Only {$ticket->remaining_quota} remaining."
                    );
                }

                $invoice = $this->generateInvoiceNumber();
                $unitPrice = self::TICKET_PRICE;
                $basePrice = $unitPrice * $validated['quantity'];

                $ticket->decrement('remaining_quota', $validated['quantity']);

                $transaction = Transaction::query()->create([
                    'ticket_id'      => $ticket->id,
                    'invoice_number' => $invoice,
                    'buyer_name'     => $validated['name'],
                    'buyer_email'    => $validated['email'],
                    'buyer_whatsapp' => '0000000000',
                    'quantity'       => $validated['quantity'],
                    'base_price'     => $basePrice,
                    'unique_code'    => 0,
                    'total_price'    => $basePrice,
                    'status'         => 'SUCCESS',
                ]);

                $codes = [];
                $now   = now();
                for ($i = 0; $i < $transaction->quantity; $i++) {
                    $attempts = 0;
                    do {
                        $code   = 'TKT-' . strtoupper(Str::random(5)) . '-' . rand(10000, 99999) . '-' . ($i + 1);
                        $exists = in_array($code, array_column($codes, 'unique_ticket_code'))
                            || TicketCode::query()->where('unique_ticket_code', '=', $code)->exists();
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

                TicketCode::query()->insert($codes);

                return $transaction->fresh();
            });

            cache()->forget('admin_dashboard_stats');
            cache()->forget('tickets_available');
            cache()->forget('admin_tickets_list');
            cache()->forget('admin_tickets_list_api');

            return $this->successResponse(
                $this->formatTransaction($transaction),
                'Manual ticket created and approved successfully',
                201
            );
        } catch (\RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('API Manual Ticket Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to create manual ticket', 500);
        }
    }

    private function formatTransaction(Transaction $tx): array
    {
        return [
            'id'                => $tx->id,
            'invoice'           => $tx->invoice_number,
            'buyer_name'        => $tx->buyer_name,
            'email'             => $tx->buyer_email,
            'ticket_quantity'   => $tx->quantity,
            'status'            => $tx->status,
            'payment_proof_url' => $tx->payment_proof
                ? url('api/images/proofs/' . $tx->payment_proof)
                : null,
            'created_at'        => $tx->created_at?->toIso8601String(),
        ];
    }

    private function generateInvoiceNumber(): string
    {
        $prefix   = 'REV-' . now()->format('Ym') . '-';
        $attempts = 0;

        do {
            $suffix  = strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 6));
            $invoice = $prefix . $suffix;
            $exists  = Transaction::query()->where('invoice_number', '=', $invoice)->exists();
            $attempts++;
        } while ($exists && $attempts < 10);

        if ($exists) {
            throw new \RuntimeException('Failed to generate a unique invoice number.');
        }

        return $invoice;
    }
}
