<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TableReservationController extends Controller
{
    public function create()
    {
        $promos = Promo::active()
            ->where('promo_type', 'reservation')
            ->get();
            
        return view('reservation.table.create', compact('promos'));
    }
    
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'number_of_guests' => 'required|integer|min:1|max:20'
        ]);
        
        $availability = TableReservation::checkAvailability(
            $validated['reservation_date'],
            $validated['reservation_time'],
            $validated['number_of_guests']
        );
        
        return response()->json($availability);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string',
            'promo_code' => 'nullable|string|exists:promos,promo_code'
        ]);
        
        $availability = TableReservation::checkAvailability(
            $validated['reservation_date'],
            $validated['reservation_time'],
            $validated['number_of_guests']
        );
        
        if (!$availability['available']) {
            return back()->with('error', 'Maaf, meja tidak tersedia pada waktu yang dipilih.');
        }
        
        $totalEstimate = 50000 * $validated['number_of_guests'];
        $downPayment = $totalEstimate * 0.3;
        
        try {
            DB::beginTransaction();
            
            $reservation = TableReservation::create([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'number_of_guests' => $validated['number_of_guests'],
                'special_requests' => $validated['special_requests'],
                'down_payment' => $downPayment,
                'status' => 'pending'
            ]);
            
            DB::commit();
            
            return redirect()->route('reservation.table.success', $reservation->booking_code)
                ->with('success', 'Reservasi berhasil dibuat. Silakan lakukan pembayaran DP sebesar Rp ' . number_format($downPayment, 0, ',', '.'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat reservasi: ' . $e->getMessage());
        }
    }
    
    public function success($bookingCode)
    {
        $reservation = TableReservation::where('booking_code', $bookingCode)->firstOrFail();
        return view('reservation.table.success', compact('reservation'));
    }
    
    public function view($bookingCode)
    {
        $reservation = TableReservation::where('booking_code', $bookingCode)->firstOrFail();
        return view('reservation.table.view', compact('reservation'));
    }
    
    public function cancel(Request $request, $bookingCode)
    {
        $reservation = TableReservation::where('booking_code', $bookingCode)->firstOrFail();
        
        if ($reservation->status === 'confirmed') {
            return back()->with('error', 'Reservasi yang sudah dikonfirmasi tidak dapat dibatalkan.');
        }
        
        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason
        ]);
        
        return redirect()->route('branding.home')->with('success', 'Reservasi berhasil dibatalkan.');
    }
}