<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TicketCode;

class MootaWebhookController extends Controller
{
    /**
     * Handle the incoming Moota webhook payload.
     *
     * Expected payload format (array of mutations):
     * [
     *   {
     *     "id": "mut-123",
     *     "bank_id": "bank-123",
     *     "account_number": "1234567890",
     *     "date": "2023-10-27 10:00:00",
     *     "amount": 50123,
     *     "description": "TRANSFER DARI JOHN DOE",
     *     "type": "CR",
     *     "balance": 1500000
     *   },
     *   ...
     * ]
     */
    public function handle(Request $request)
    {
        $mutations = $request->json()->all();

        if (!is_array($mutations)) {
            Log::warning('Moota webhook: Payload is not an array', ['payload' => $mutations]);
            return response()->json(['message' => 'Invalid payload format.'], 400);
        }

        $processedCount = 0;

        foreach ($mutations as $mutation) {
            // We only care about Credit (incoming) mutations
            if (!isset($mutation['type']) || $mutation['type'] !== 'CR') {
                continue;
            }

            if (!isset($mutation['amount'])) {
                continue;
            }

            $amount = (int) $mutation['amount'];

            // Find a pending transaction with the exact total_price matching the mutation amount
            // Since unique_code is 1-999, total_price is highly likely to be unique for pending transactions
            $transaction = Transaction::where('total_price', $amount)
                                      ->where('status', 'pending')
                                      ->first();

            if ($transaction) {
                try {
                    DB::transaction(function () use ($transaction) {
                        // Mark as success
                        $transaction->status = 'success';
                        $transaction->save();

                        // Generate one TicketCode per quantity purchased
                        for ($i = 0; $i < $transaction->quantity; $i++) {
                            TicketCode::create([
                                'transaction_id' => $transaction->id,
                                'code'           => $transaction->generateTicketCode($i),
                                'status'         => 'active',
                            ]);
                        }
                        
                        // Dispatch a Job to generate the PDF and send the E-Ticket email
                        \App\Jobs\ProcessTicketPurchase::dispatch($transaction);
                    });

                    Log::info('Moota webhook: Transaction matched and processed successfully.', [
                        'transaction_id' => $transaction->id,
                        'invoice_number' => $transaction->invoice_number,
                        'amount'         => $amount
                    ]);
                    
                    $processedCount++;

                } catch (\Exception $e) {
                    Log::error('Moota webhook: Failed to process matched transaction.', [
                        'transaction_id' => $transaction->id,
                        'error'          => $e->getMessage()
                    ]);
                }
            } else {
                Log::debug('Moota webhook: No pending transaction found for amount.', ['amount' => $amount]);
            }
        }

        return response()->json([
            'message' => 'Webhook processed successfully',
            'processed' => $processedCount
        ]);
    }
}
