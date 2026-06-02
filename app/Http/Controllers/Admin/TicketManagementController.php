<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use Illuminate\Http\Request;

class TicketManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = PoolTicket::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->date);
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.tickets.index', compact('tickets'));
    }
    
    public function show(PoolTicket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }
    
    /**
     * Verifikasi pembayaran tiket (manual oleh admin)
     * Endpoint ini dipanggil via AJAX
     */
    public function verify(PoolTicket $ticket)
    {
        try {
            // 🔥 HANYA bisa verifikasi jika payment_status = 'payment_verified'
            if ($ticket->payment_status !== 'payment_verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak dapat diverifikasi. Status saat ini: ' . $ticket->payment_status
                ]);
            }
            
            $ticket->update([
                'payment_status' => 'paid',
                'status' => 'active'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran tiket berhasil diverifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function checkCapacity(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $capacity = PoolTicket::checkCapacity($date);
        
        return response()->json($capacity);
    }
}