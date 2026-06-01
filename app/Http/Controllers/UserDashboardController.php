<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user's purchased tickets.
     */
    public function myTickets()
    {
        $email = Auth::user()->email;
        
        $transactions = Transaction::select([
                'id', 
                'invoice_number', 
                'buyer_email', 
                'buyer_whatsapp', 
                'quantity', 
                'total_price', 
                'status', 
                'download_token', 
                'created_at'
            ])
            ->with(['ticketCodes:id,transaction_id,unique_ticket_code,is_scanned'])
            ->where('buyer_email', '=', $email)
            ->orWhere('buyer_whatsapp', '=', $email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.dashboard', compact('transactions'));
    }
}
