<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display the payment instruction page for a given invoice.
     */
    public function show(string $invoice)
    {
        $transaction = Transaction::query()->with('ticket')
            ->where('invoice_number', '=', $invoice)
            ->firstOrFail();

        return view('payment.show', compact('transaction'));
    }

    /**
     * Check the current status of a transaction (AJAX polling).
     */
    public function status(string $invoice)
    {
        $transaction = Transaction::query()->where('invoice_number', '=', $invoice)
            ->select(['invoice_number', 'status', 'updated_at'])
            ->firstOrFail();

        return response()->json([
            'status'     => $transaction->status,
            'updated_at' => $transaction->updated_at->toISOString(),
        ]);
    }
}
