<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardApiController extends Controller
{
    use ApiResponser;

    /**
     * Get dashboard stats for the Gatekeeper mobile app.
     */
    public function stats(): JsonResponse
    {
        try {
            $totalTicketsSold = Transaction::where('status', 'SUCCESS')->sum('quantity');
            $totalRevenue = Transaction::where('status', 'SUCCESS')->sum('total_price');
            
            // Get 5 most recent transactions
            $recentTransactions = Transaction::select(['invoice_number', 'buyer_name', 'status', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($tx) {
                    return [
                        'invoice_number' => $tx->invoice_number,
                        'buyer_name' => $tx->buyer_name,
                        'status' => $tx->status,
                        'time' => $tx->created_at->diffForHumans()
                    ];
                });

            return $this->successResponse([
                'tickets_sold' => $totalTicketsSold,
                'revenue' => $totalRevenue,
                'recent_transactions' => $recentTransactions
            ], 'Dashboard stats retrieved successfully');
        } catch (\Exception $e) {
            Log::error('API Dashboard Stats Error: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve dashboard stats', 500);
        }
    }
}
