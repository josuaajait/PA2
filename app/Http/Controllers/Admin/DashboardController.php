<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Models\PoolTicket;
use App\Models\Payment;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Testimonial;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // 🔥 STATISTIK UTAMA
        $stats = [
            // Hari ini
            'today_reservations' => TableReservation::whereDate('created_at', $today)->count(),
            'today_tickets' => PoolTicket::whereDate('created_at', $today)->count(),
            'today_income' => $this->getTodayIncome(),
            'pending_reservations' => TableReservation::where('status', 'pending')->count(),
            
            // Statistik Tiket
            'total_tickets_sold' => PoolTicket::sum('number_of_tickets'),
            'total_ticket_income' => PoolTicket::where('payment_status', 'paid')->sum('total_amount'),
            'avg_ticket_price' => PoolTicket::where('payment_status', 'paid')->avg('total_amount') ?? 0,
            
            // Pending payments (menunggu verifikasi admin)
            'pending_payments' => $this->getPendingPayments(),
            
            // Lainnya
            'pending_testimonials' => Testimonial::where('is_approved', false)->count(),
        ];
        
        // 🔥 DATA UNTUK CHART (7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Hitung pendapatan dari reservasi + tiket
            $reservationIncome = TableReservation::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('down_payment');
                
            $ticketIncome = PoolTicket::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            
            $chartData[] = [
                'date' => $date->format('d M'),
                'reservations' => TableReservation::whereDate('created_at', $date)->count(),
                'tickets' => PoolTicket::whereDate('created_at', $date)->count(),
                'income' => $reservationIncome + $ticketIncome, // Total pendapatan
            ];
        }
        
        return view('admin.dashboard', compact('stats', 'chartData'));
    }
    
    /**
     * Hitung total pendapatan hari ini (dari reservasi + tiket)
     */
    private function getTodayIncome()
    {
        $today = Carbon::today();
        
        // Pendapatan dari reservasi (DP yang sudah lunas)
        $reservationIncome = TableReservation::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('down_payment');
        
        // Pendapatan dari tiket (yang sudah lunas)
        $ticketIncome = PoolTicket::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        
        return $reservationIncome + $ticketIncome;
    }
    
    /**
     * Hitung jumlah pembayaran yang menunggu verifikasi
     */
    private function getPendingPayments()
    {
        $pendingReservations = TableReservation::where('payment_status', 'payment_verified')->count();
        $pendingTickets = PoolTicket::where('payment_status', 'payment_verified')->count();
        
        return $pendingReservations + $pendingTickets;
    }
}