<?php

namespace App\Http\Controllers;

use App\Models\TicketCode;
use Illuminate\Http\Request;

class GatekeeperController extends Controller
{
    /**
     * Display the cyberpunk gate scanner view.
     * Accessible by admin users.
     */
    public function index()
    {
        return view('admin.gatekeeper');
    }

    /**
     * Process a ticket scan via AJAX.
     * Returns JSON: valid | already_scanned | invalid
     */
    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->input('code')));

        // Find the ticket code with eager-loaded transaction and ticket
        $ticketCode = TicketCode::with(['transaction.ticket'])
            ->where('unique_ticket_code', '=', $code)
            ->first();

        // ── Not found ────────────────────────────────────────────────────────
        if (!$ticketCode) {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Kode tiket tidak ditemukan atau tidak valid.',
            ], 404);
        }

        $transaction = $ticketCode->transaction;

        // ── Transaction not SUCCESS (note: uppercase STATUS enum) ─────────────
        if ($transaction->status !== 'SUCCESS') {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Tiket ini belum dikonfirmasi pembayarannya.',
            ], 400);
        }

        // ── Already scanned ───────────────────────────────────────────────────
        if ($ticketCode->is_scanned) {
            return response()->json([
                'status'     => 'duplicate',
                'message'    => 'Tiket ini sudah di-scan sebelumnya!',
                'buyer_name' => $transaction->buyer_name,
                'scanned_at' => $ticketCode->scanned_at?->format('d M Y H:i:s'),
            ], 409);
        }

        // ── Valid — mark as scanned ───────────────────────────────────────────
        $ticketCode->update([
            'is_scanned' => true,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'status'      => 'valid',
            'message'     => 'Tiket Valid! Selamat datang.',
            'buyer_name'  => $transaction->buyer_name,
            'ticket_name' => $transaction->ticket->ticket_name ?? 'Tiket Reguler',
            'invoice'     => $transaction->invoice_number,
        ], 200);
    }
}
