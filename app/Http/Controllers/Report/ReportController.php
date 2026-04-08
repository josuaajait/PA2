<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Models\PoolTicket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function reservations(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();
        
        $reservations = TableReservation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();
        
        $summary = [
            'total' => $reservations->count(),
            'confirmed' => $reservations->where('status', 'confirmed')->count(),
            'pending' => $reservations->where('status', 'pending')->count(),
            'cancelled' => $reservations->where('status', 'cancelled')->count(),
            'total_guests' => $reservations->sum('number_of_guests'),
            'total_income' => $reservations->where('payment_status', 'paid')->sum('down_payment')
        ];
        
        return view('admin.reports.reservations', compact('reservations', 'summary', 'startDate', 'endDate'));
    }
    
    public function tickets(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();
        
        $tickets = PoolTicket::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();
        
        $summary = [
            'total' => $tickets->count(),
            'paid' => $tickets->where('payment_status', 'paid')->count(),
            'unpaid' => $tickets->where('payment_status', 'unpaid')->count(),
            'total_tickets' => $tickets->sum('number_of_tickets'),
            'total_income' => $tickets->where('payment_status', 'paid')->sum('total_amount')
        ];
        
        return view('admin.reports.tickets', compact('tickets', 'summary', 'startDate', 'endDate'));
    }
    
    public function income(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();
        
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'verified')
            ->get();
        
        $dailyIncome = [];
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= $endDate) {
            $dailyIncome[] = [
                'date' => $currentDate->format('Y-m-d'),
                'amount' => $payments->where('created_at', '>=', $currentDate->copy()->startOfDay())
                    ->where('created_at', '<=', $currentDate->copy()->endOfDay())
                    ->sum('amount')
            ];
            $currentDate->addDay();
        }
        
        $summary = [
            'total_income' => $payments->sum('amount'),
            'reservation_income' => $payments->where('payable_type', TableReservation::class)->sum('amount'),
            'ticket_income' => $payments->where('payable_type', PoolTicket::class)->sum('amount'),
            'total_transactions' => $payments->count()
        ];
        
        return view('admin.reports.income', compact('payments', 'dailyIncome', 'summary', 'startDate', 'endDate'));
    }
    
    public function export(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();
        
        // Logic untuk export Excel/PDF
        return redirect()->back()->with('success', 'Laporan berhasil diekspor');
    }
}