<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\TableReservation;
use App\Models\PoolTicket;
use App\Models\Payment;
use App\Exports\ReservationsExport;
use App\Exports\TicketsExport;
use App\Exports\IncomeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Halaman utama laporan
     */
    public function index()
    {
        return view('admin.reports.index');
    }
    
    /**
     * Laporan Reservasi
     */
    public function reservations(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $query = TableReservation::whereBetween('reservation_date', [$startDate, $endDate]);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $reservations = $query->orderBy('reservation_date', 'desc')->paginate(20);
        
        $summary = [
            'total' => $query->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'total_guests' => $query->sum('number_of_guests'),
        ];
        
        // Chart data
        $chartLabels = [];
        $chartData = [];
        $dailyReservations = TableReservation::selectRaw('DATE(reservation_date) as date, COUNT(*) as total')
            ->whereBetween('reservation_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        foreach ($dailyReservations as $item) {
            $chartLabels[] = $item->date;
            $chartData[] = $item->total;
        }
        
        return view('admin.reports.reservations', compact('reservations', 'summary', 'startDate', 'endDate', 'chartLabels', 'chartData'));
    }
    
    /**
     * Laporan Tiket Kolam
     */
    public function tickets(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $query = PoolTicket::whereBetween('visit_date', [$startDate, $endDate]);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tickets = $query->orderBy('visit_date', 'desc')->paginate(20);
        
        $summary = [
            'total_transactions' => $query->count(),
            'total_tickets' => $query->sum('number_of_tickets'),
            'total_income' => $query->where('payment_status', 'paid')->sum('total_amount'),
            'average_price' => $query->avg('price_per_ticket') ?? 0,
        ];
        
        // Chart data by ticket type
        $typeLabels = ['Dewasa', 'Anak', 'Keluarga'];
        $typeData = [
            $query->where('ticket_type', 'adult')->count(),
            $query->where('ticket_type', 'child')->count(),
            $query->where('ticket_type', 'family')->count(),
        ];
        
        // Chart trend
        $trendLabels = [];
        $trendData = [];
        $dailySales = PoolTicket::selectRaw('DATE(visit_date) as date, SUM(number_of_tickets) as total')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        foreach ($dailySales as $item) {
            $trendLabels[] = $item->date;
            $trendData[] = $item->total;
        }
        
        return view('admin.reports.tickets', compact('tickets', 'summary', 'startDate', 'endDate', 
            'typeLabels', 'typeData', 'trendLabels', 'trendData'));
    }
    
    /**
     * Laporan Pemasukan
     */
    public function income(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $query = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'verified');
        
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $summary = [
            'total_income' => $query->sum('amount'),
            'reservation_income' => (clone $query)->where('payable_type', TableReservation::class)->sum('amount'),
            'ticket_income' => (clone $query)->where('payable_type', PoolTicket::class)->sum('amount'),
            'total_transactions' => $query->count(),
        ];
        
        // Daily income chart
        $dailyLabels = [];
        $dailyData = [];
        $dailyIncome = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'verified')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        foreach ($dailyIncome as $item) {
            $dailyLabels[] = $item->date;
            $dailyData[] = $item->total;
        }
        
        return view('admin.reports.income', compact('payments', 'summary', 'startDate', 'endDate', 
            'dailyLabels', 'dailyData'));
    }
    
    /**
     * Export reservations to Excel
     */
    public function exportReservations(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;
        
        $export = new ReservationsExport($startDate, $endDate, $status);
        $filename = 'reservations_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    /**
     * Export tickets to Excel
     */
    public function exportTickets(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $export = new TicketsExport($startDate, $endDate);
        $filename = 'tickets_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    /**
     * Export income to Excel
     */
    public function exportIncome(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $paymentType = $request->payment_type;
        
        $export = new IncomeExport($startDate, $endDate, $paymentType);
        $filename = 'income_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
}