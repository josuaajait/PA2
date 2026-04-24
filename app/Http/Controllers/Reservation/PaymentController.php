<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\TableReservation;
use App\Models\PoolTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function uploadProof(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => 'required|string',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_method' => 'required|in:transfer,credit_card,e_wallet',
            'bank_name' => 'required_if:payment_method,transfer|nullable|string',
            'account_number' => 'required_if:payment_method,transfer|nullable|string',
            'account_name' => 'required_if:payment_method,transfer|nullable|string',
            'amount' => 'required|numeric|min:0'
        ]);
        
        $reservation = TableReservation::where('booking_code', $validated['booking_code'])->first();
        $ticket = PoolTicket::where('ticket_code', $validated['booking_code'])->first();
        $payable = $reservation ?? $ticket;
        
        if (!$payable) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
        
        $path = $request->file('payment_proof')->store('payments', 'public');
        
        try {
            DB::beginTransaction();
            
            Payment::create([
                'payment_code' => 'PAY-' . strtoupper(uniqid()),
                'payable_type' => get_class($payable),
                'payable_id' => $payable->id,
                'amount' => $validated['amount'],
                'payment_type' => $reservation ? 'down_payment' : 'full_payment',
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'payment_proof' => $path,
                'payment_status' => 'pending'
            ]);
            
            if ($reservation) {
                $reservation->update(['payment_status' => 'partial', 'payment_proof' => $path]);
            } else {
                $ticket->update(['payment_status' => 'paid', 'payment_proof' => $path]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
        }
    }
    
    public function status($bookingCode)
    {
        $reservation = TableReservation::where('booking_code', $bookingCode)->first();
        $ticket = PoolTicket::where('ticket_code', $bookingCode)->first();
        $data = $reservation ?? $ticket;
        
        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        
        return response()->json([
            'booking_code' => $bookingCode,
            'payment_status' => $data->payment_status,
            'status' => $data->status,
            'amount' => $data->down_payment ?? $data->total_amount
        ]);
    }

    
}