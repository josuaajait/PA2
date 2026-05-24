<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use App\Models\Promo;
use App\Models\User;
use App\Notifications\TicketNotification;
use App\Notifications\AdminNewTicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PromoServiceClient;

class TicketController extends Controller
{
    public function create()
    {
        $promos = Promo::active()
            ->where('promo_type', 'ticket')
            ->get();

        $ticketPrices = [
            'adult'  => 35000,
            'child'  => 25000,
            'family' => 100000
        ];

        return view('reservation.ticket.create', compact('promos', 'ticketPrices'));
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
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'customer_phone'    => 'required|string|max:20',
            'visit_date'        => 'required|date|after_or_equal:today',
            'visit_time'        => 'nullable',
            'ticket_type'       => 'required|in:adult,child,family',
            'number_of_tickets' => 'required|integer|min:1',
            'promo_code'        => 'nullable|string', // Hanya validasi string, tidak perlu exists karena akan validasi via service
        ]);

        $capacity = PoolTicket::checkCapacity($validated['visit_date']);
        if ($capacity['available'] < $validated['number_of_tickets']) {
            return back()->with('error', 'Maaf, tiket tidak tersedia untuk tanggal tersebut. Sisa tiket: ' . $capacity['available']);
        }

        $prices = [
            'adult'  => 35000,
            'child'  => 25000,
            'family' => 100000
        ];
        $pricePerTicket = $prices[$validated['ticket_type']];
        $totalAmount = $pricePerTicket * $validated['number_of_tickets'];

        // Validasi promo via microservice
        $discount = 0;
        if ($request->filled('promo_code')) {
            try {
                $promoService = app(PromoServiceClient::class);
                $result = $promoService->validatePromo($request->promo_code, $totalAmount);
                if ($result && isset($result['valid']) && $result['valid']) {
                    $discount = $result['discount_amount'];
                    $totalAmount = $result['final_amount']; // atau kurangi sendiri
                } else {
                    return back()->withInput()->with('error', 'Kode promo tidak valid atau sudah habis masa berlaku.');
                }
            } catch (\Exception $e) {
                Log::error('Promo validation error: ' . $e->getMessage());
                // Bisa tetap lanjut tanpa promo
            }
        }

        try {
            DB::beginTransaction();

            // Generate ticket code
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
                'total_amount'      => $totalAmount,
                'payment_status'    => 'unpaid',
                'status'            => 'active',
                'user_id'           => $userId,
            ]);

            // Jika ada diskon, bisa simpan info promo (opsional)
            if ($discount > 0) {
                // $ticket->update(['promo_code' => $request->promo_code, 'discount_amount' => $discount]);
            }

            // Notifikasi ke customer
            if ($userId) {
                $customer = User::find($userId);
                if ($customer) {
                    $customer->notify(new TicketNotification($ticket, 'purchased'));
                }
            }

            // Notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminNewTicketNotification($ticket));
            }

            DB::commit();

            return redirect()->route('reservation.ticket.success', $ticket->ticket_code)
                ->with('success', 'Pemesanan tiket berhasil. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memesan tiket: ' . $e->getMessage());
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
}