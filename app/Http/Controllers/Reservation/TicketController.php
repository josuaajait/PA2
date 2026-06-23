<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use App\Models\User;
use App\Notifications\TicketNotification;
use App\Notifications\AdminNewTicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function create()
    {
        $ticketPrices = [
            'adult'  => 35000,
            'child'  => 25000,
            'family' => 100000
        ];

        return view('reservation.ticket.create', compact('ticketPrices'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'ticket_type'       => 'required|in:adult,child,family',
            'number_of_tickets' => 'required|integer|min:1',
            'visit_date'        => 'required|date|after_or_equal:today'
        ]);

        $prices = [
            'adult'  => 35000,
            'child'  => 25000,
            'family' => 100000
        ];

        $pricePerTicket = $prices[$validated['ticket_type']];
        $total          = $pricePerTicket * $validated['number_of_tickets'];
        $capacity       = PoolTicket::checkCapacity($validated['visit_date']);

        return response()->json([
            'price_per_ticket' => $pricePerTicket,
            'total'            => $total,
            'capacity'         => $capacity
        ]);
    }

    public function store(Request $request)
    {
        // Log semua request
        Log::info('=== FULL REQUEST ===', $request->all());
        Log::info('VERSI BARU DIPANGGIL - cek regex phone', ['phone' => $request->customer_phone]);
    
        
        // Validasi
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'customer_phone'   => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'visit_date'        => 'required|date|after_or_equal:today',
            'visit_time'        => 'nullable',
            'ticket_type'       => 'required|in:adult,child,family',
            'number_of_tickets' => 'required|integer|min:1',
            'promo_code'        => 'nullable|string',
            'final_total_amount' => 'nullable|numeric|min:0',
            'discount_amount'   => 'nullable|numeric|min:0',
        ]);

        $capacity = PoolTicket::checkCapacity($validated['visit_date']);
        if ($capacity['available'] < $validated['number_of_tickets']) {
            return back()->withInput()->with('error', 'Maaf, tiket tidak tersedia untuk tanggal tersebut. Sisa tiket: ' . $capacity['available']);
        }

        $prices = [
            'adult'  => 35000,
            'child'  => 25000,
            'family' => 100000
        ];
        $pricePerTicket = $prices[$validated['ticket_type']];
        $normalTotal = $pricePerTicket * $validated['number_of_tickets'];
        
        // 🔥 PAKSA MENGGUNAKAN final_total_amount DARI REQUEST
        // Jika ada final_total_amount, gunakan itu. Jika tidak, pakai normal.
        $totalAmount = $normalTotal;
        $discount = 0;
        
        if ($request->has('final_total_amount') && $request->final_total_amount > 0) {
            $totalAmount = $request->final_total_amount;
            $discount = $request->discount_amount ?? ($normalTotal - $totalAmount);
        }
        
        // 🔥 LOG NILAI YANG AKAN DISIMPAN
        Log::info('💰💰💰 VALUE TO SAVE 💰💰💰', [
            'normal_total' => $normalTotal,
            'final_total_amount_from_request' => $request->final_total_amount,
            'total_amount_to_save' => $totalAmount,
            'discount' => $discount,
            'promo_code' => $request->promo_code
        ]);

        try {
            DB::beginTransaction();

            $ticketCode = 'TKT-' . strtoupper(uniqid());
            while (PoolTicket::where('ticket_code', $ticketCode)->exists()) {
                $ticketCode = 'TKT-' . strtoupper(uniqid());
            }

            $userId = Auth::check() ? Auth::id() : null;
            if (!$userId) {
                $existingUser = User::where('email', $validated['customer_email'])->first();
                if ($existingUser) {
                    $userId = $existingUser->id;
                }
            }

            $ticket = PoolTicket::create([
                'ticket_code'       => $ticketCode,
                'customer_name'     => $validated['customer_name'],
                'customer_email'    => $validated['customer_email'],
                'customer_phone'    => $validated['customer_phone'],
                'visit_date'        => $validated['visit_date'],
                'visit_time'        => $validated['visit_time'] ?? null,
                'ticket_type'       => $validated['ticket_type'],
                'number_of_tickets' => $validated['number_of_tickets'],
                'price_per_ticket'  => $pricePerTicket,
                'total_amount'      => $totalAmount,  // 🔥 INI YANG AKAN TERSIMPAN
                'payment_status'    => 'unpaid',
                'status'            => 'pending',
                'user_id'           => $userId,
            ]);

            Log::info('💾💾💾 TICKET SAVED 💾💾💾', [
                'ticket_code' => $ticketCode,
                'total_amount_saved' => $ticket->total_amount,
                'normal_total' => $normalTotal,
                'discount' => $discount
            ]);

            DB::commit();

            return redirect()->route('reservation.ticket.success', $ticket->ticket_code)
                ->with('success', 'Pemesanan tiket berhasil. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket purchase error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memesan tiket: ' . $e->getMessage());
        }
    }

    public function success($ticketCode)
    {
        $ticket = PoolTicket::where('ticket_code', $ticketCode)->firstOrFail();
        return view('reservation.ticket.success', compact('ticket'));
    }

    public function view($ticketCode)
    {
        $ticket = PoolTicket::where('ticket_code', $ticketCode)->firstOrFail();
        return view('reservation.ticket.view', compact('ticket'));
    }

    // app/Http/Controllers/Reservation/TicketController.php

    // Tambahkan method ini:

    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'ticket_code'    => 'required|exists:pool_tickets,ticket_code',
            'bank_from'      => 'required|string',
            'account_name'   => 'required|string|max:255',
            'payment_proof'  => 'required|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        $ticket = PoolTicket::where('ticket_code', $request->ticket_code)->firstOrFail();

        // Cek apakah sudah pernah upload
        if ($ticket->payment_proof && $ticket->payment_status !== 'rejected') {
            return back()->with('error', 'Anda sudah mengupload bukti pembayaran. Silakan tunggu verifikasi admin.');
        }

        // Upload file
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $ticket->ticket_code . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs/tickets', $fileName, 'public');
            
            $ticket->update([
                'payment_proof'   => $path,
                'payment_method'  => 'transfer',
                'payment_status'  => 'waiting_payment', // Status menunggu verifikasi
                'bank_from'       => $request->bank_from,
                'account_name'    => $request->account_name,
            ]);
        }

        // Kirim notifikasi ke admin (real-time)
        // event(new NewPaymentUploadedEvent($ticket));

        return redirect()->route('reservation.ticket.view', $ticket->ticket_code)
            ->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi.');
    }
}