<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket customer
     */
    public function index()
    {
        $tickets = PoolTicket::where('customer_email', Auth::user()->email)
            ->orWhere('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.tickets', compact('tickets'));
    }

    /**
     * Detail tiket
     */
    public function show($ticketCode)
    {
        $ticket = PoolTicket::where('ticket_code', $ticketCode)
            ->where(function($query) {
                $query->where('customer_email', Auth::user()->email)
                      ->orWhere('user_id', Auth::id());
            })
            ->firstOrFail();
            
        return view('customer.ticket-detail', compact('ticket'));
    }

    
}