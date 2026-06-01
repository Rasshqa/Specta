<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCode;
use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardApiController extends Controller
{
    use ApiResponser;

    /**
     * Get dashboard stats for the mobile admin command center.
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = cache()->remember('admin_dashboard_stats', 60, function () {
                $agg = Transaction::query()->selectRaw("
                    COUNT(*) AS total_transactions,
                    SUM(CASE WHEN status = 'PENDING_PROOF' THEN 1 ELSE 0 END) AS pending_transactions,
                    SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) AS success_transactions,
                    SUM(CASE WHEN status = 'REJECTED' THEN 1 ELSE 0 END) AS expired_transactions,
                    SUM(CASE WHEN status = 'SUCCESS' THEN total_price ELSE 0 END) AS total_revenue,
                    SUM(CASE WHEN status = 'SUCCESS' THEN quantity ELSE 0 END) AS tickets_sold
                ", [])->first();

                $ticketAgg = TicketCode::query()->selectRaw("
                    COUNT(*) AS qr_generated,
                    SUM(CASE WHEN is_scanned = 1 THEN 1 ELSE 0 END) AS qr_scanned,
                    SUM(CASE WHEN is_scanned = 1 AND DATE(scanned_at) = CURDATE() THEN 1 ELSE 0 END) AS scanned_today
                ", [])->first();

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

            $tickets = cache()->remember('admin_tickets_list_api', 3600, function () {
                return Ticket::query()->select(['id', 'ticket_name', 'quota', 'remaining_quota'])
                    ->get()
                    ->map(function (Ticket $ticket) {
                        $sold = $ticket->quota - $ticket->remaining_quota;
                        $pct  = $ticket->quota > 0
                            ? round(($sold / $ticket->quota) * 100)
                            : 0;

                        return [
                            'name'             => $ticket->ticket_name,
                            'quota'            => $ticket->quota,
                            'remaining_quota'  => $ticket->remaining_quota,
                            'sold'             => $sold,
                            'fill_percentage'  => $pct,
                        ];
                    });
            });

            $recentTransactions = Transaction::query()->select([
                    'invoice_number', 'buyer_name', 'status', 'created_at',
                ])
                ->orderByDesc('created_at')
                ->take(5)
                ->get()
                ->map(function ($tx) {
                    return [
                        'invoice_number' => $tx->invoice_number,
                        'buyer_name'     => $tx->buyer_name,
                        'status'         => $tx->status,
                        'time'           => $tx->created_at->diffForHumans(),
                    ];
                });

            return $this->successResponse([
                ...$stats,
                'revenue'             => $stats['total_revenue'],
                'ticket_quotas'       => $tickets,
                'recent_transactions' => $recentTransactions,
            ], 'Dashboard stats retrieved successfully');
        } catch (\Exception $e) {
            Log::error('API Dashboard Stats Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve dashboard stats', 500);
        }
    }
}
