<?php

namespace App\Http\Controllers;

use App\Models\TicketCode;
use Illuminate\Http\Request;

class GatekeeperController extends Controller
{
    /**
     * Display the scanner view.
     */
    public function index()
    {
        return view('gatekeeper.scanner');
    }

    /**
     * Process the ticket scan.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');

        // Find the ticket code and eager load the related transaction and ticket
        $ticketCode = TicketCode::with(['transaction.ticket'])->where('unique_ticket_code', $code)->first();

        if (!$ticketCode) {
            return response()->json([
                'message' => 'Kode tiket tidak ditemukan atau palsu.',
            ], 404);
        }

        $transaction = $ticketCode->transaction;

        // Ensure the transaction is actually confirmed (success)
        if ($transaction->status !== 'success') {
            return response()->json([
                'message' => 'Tiket ini belum lunas atau transaksi dibatalkan.',
            ], 400);
        }

        // If the ticket was already scanned
        if ($ticketCode->is_scanned) {
            return response()->json([
                'message' => 'Tiket ini sudah di-scan sebelumnya!',
                'scanned_at' => $ticketCode->scanned_at->format('d M Y H:i:s'),
                'buyer_name' => $transaction->buyer_name,
            ], 409); // Conflict
        }

        // Valid and first time scan, mark as scanned
        $ticketCode->is_scanned = true;
        $ticketCode->scanned_at = now();
        $ticketCode->save();

        return response()->json([
            'message' => 'Tiket Valid!',
            'buyer_name' => $transaction->buyer_name,
            'buyer_class' => $transaction->buyer_class,
            'ticket_name' => $transaction->ticket->ticket_name ?? $transaction->ticket->name,
            'invoice' => $transaction->invoice_number,
        ], 200);
    }
}
