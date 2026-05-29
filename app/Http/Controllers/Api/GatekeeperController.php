<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketCode;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GatekeeperController extends Controller
{
    use ApiResponser;

    /**
     * Validate a scanned ticket code.
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        try {
            $codeStr = $request->input('ticket_code');
            $ticketCode = TicketCode::with('transaction')->where('unique_ticket_code', $codeStr)->first();

            // 1. Check if code exists
            if (!$ticketCode) {
                return $this->errorResponse('INVALID TICKET: Code not found in database', 404);
            }

            // 2. Check if transaction is actually successful (paid)
            if ($ticketCode->transaction && !$ticketCode->transaction->isSuccess()) {
                return $this->errorResponse('PAYMENT PENDING: This ticket has not been fully paid', 400);
            }

            // 3. Check if already scanned
            if ($ticketCode->is_scanned) {
                return $this->errorResponse('ALREADY SCANNED at ' . $ticketCode->scanned_at->format('H:i:s d-m-Y'), 400);
            }

            // 4. Mark as scanned
            $ticketCode->markAsScanned();

            return $this->successResponse([
                'ticket_code' => $ticketCode->unique_ticket_code,
                'buyer' => $ticketCode->transaction->buyer_name ?? 'Unknown',
            ], 'ACCESS GRANTED');

        } catch (\Exception $e) {
            Log::error('API Gatekeeper Scan Error: ' . $e->getMessage());
            return $this->errorResponse('Server error during validation', 500);
        }
    }
}
