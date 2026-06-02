<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use App\Models\TableReservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Dashboard laporan
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Laporan Reservasi Meja
     */
    public function reservations(Request $request)
    {
        $query = TableReservation::query();

        if ($request->filled('start_date')) {
            $query->whereDate('reservation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('reservation_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(15);

        $summary = [
            'total'        => TableReservation::count(),
            'confirmed'    => TableReservation::where('status', 'confirmed')->count(),
            'pending'      => TableReservation::where('status', 'pending')->count(),
            'cancelled'    => TableReservation::where('status', 'cancelled')->count(),
            'total_guests' => TableReservation::sum('number_of_guests'),
        ];

        $chartData = TableReservation::selectRaw("TO_CHAR(reservation_date, 'DD Mon') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->limit(14)
            ->pluck('total', 'label');

        $chartLabels = $chartData->keys()->toArray();
        $chartValues = $chartData->values()->toArray();

        return view('admin.reports.reservations', compact('reservations', 'summary', 'chartLabels', 'chartValues'));
    }

    /**
     * Laporan Tiket Kolam
     */
    public function tickets(Request $request)
    {
        $query = PoolTicket::query();

        if ($request->filled('start_date')) {
            $query->whereDate('visit_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('visit_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15);

        $summary = [
            'total_tickets'      => PoolTicket::sum('number_of_tickets'),
            'total_transactions' => PoolTicket::count(),
            'total_income'       => PoolTicket::where('payment_status', 'paid')->sum('total_amount'),
            'average_price'      => PoolTicket::where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];

        $typeData = [
            PoolTicket::where('ticket_type', 'adult')->count(),
            PoolTicket::where('ticket_type', 'child')->count(),
            PoolTicket::where('ticket_type', 'family')->count(),
        ];
        $typeLabels = ['Dewasa', 'Anak', 'Keluarga'];

        $trendRaw = PoolTicket::selectRaw("TO_CHAR(visit_date, 'DD Mon') as label, SUM(number_of_tickets) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->limit(14)
            ->pluck('total', 'label');
        
        $trendLabels = $trendRaw->keys()->toArray();
        $trendData = $trendRaw->values()->toArray();

        return view('admin.reports.tickets', compact('tickets', 'summary', 'typeLabels', 'typeData', 'trendLabels', 'trendData'));
    }

    /**
     * Laporan Pemasukan (Gabungan Tiket + Reservasi)
     */
    public function income(Request $request)
    {
        // Kumpulkan data dari tiket yang sudah lunas
        $ticketQuery = PoolTicket::where('payment_status', 'paid');
        
        // Kumpulkan data dari reservasi yang sudah lunas
        $reservationQuery = TableReservation::where('payment_status', 'paid');
        
        // Filter tanggal
        if ($request->filled('start_date')) {
            $startDate = $request->start_date;
            $ticketQuery->whereDate('paid_at', '>=', $startDate);
            $reservationQuery->whereDate('paid_at', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $endDate = $request->end_date;
            $ticketQuery->whereDate('paid_at', '<=', $endDate);
            $reservationQuery->whereDate('paid_at', '<=', $endDate);
        }
        
        $collections = collect();
        
        // Tambah data tiket
        if (!$request->filled('payment_type') || $request->payment_type == 'full_payment') {
            $tickets = $ticketQuery->get()->map(function($ticket) {
                return (object)[
                    'payment_code' => $ticket->ticket_code,
                    'payment_type' => 'full_payment',
                    'payment_method' => $ticket->payment_method ?? 'transfer',
                    'amount' => $ticket->total_amount,
                    'payment_status' => $ticket->payment_status,
                    'created_at' => $ticket->paid_at ?? $ticket->created_at,
                ];
            });
            $collections = $collections->concat($tickets);
        }
        
        // Tambah data reservasi
        if (!$request->filled('payment_type') || $request->payment_type == 'down_payment') {
            $reservations = $reservationQuery->get()->map(function($reservation) {
                return (object)[
                    'payment_code' => $reservation->booking_code,
                    'payment_type' => 'down_payment',
                    'payment_method' => 'transfer',
                    'amount' => $reservation->down_payment ?? 50000,
                    'payment_status' => $reservation->payment_status,
                    'created_at' => $reservation->paid_at ?? $reservation->created_at,
                ];
            });
            $collections = $collections->concat($reservations);
        }
        
        // Urutkan berdasarkan tanggal
        $collections = $collections->sortByDesc('created_at');
        
        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = $collections->slice(($currentPage - 1) * $perPage, $perPage);
        
        $payments = new LengthAwarePaginator(
            $currentItems,
            $collections->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Summary
        $summary = [
            'total_income'       => $collections->sum('amount'),
            'ticket_income'      => $collections->where('payment_type', 'full_payment')->sum('amount'),
            'reservation_income' => $collections->where('payment_type', 'down_payment')->sum('amount'),
        ];
        
        // Data untuk chart
        $dailyData = [];
        $dailyLabels = [];
        
        $grouped = $collections->groupBy(function($item) {
            return $item->created_at->format('d M');
        });
        
        foreach ($grouped as $label => $items) {
            $dailyLabels[] = $label;
            $dailyData[] = $items->sum('amount');
        }
        
        // Ambil 14 terakhir
        $dailyLabels = array_slice($dailyLabels, -14);
        $dailyData = array_slice($dailyData, -14);
        
        return view('admin.reports.income', compact('payments', 'summary', 'dailyLabels', 'dailyData'));
    }

    /**
     * Export CSV Reservasi
     */
    public function exportReservations(Request $request)
    {
        $query = TableReservation::query();
        if ($request->filled('start_date')) {
            $query->whereDate('reservation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('reservation_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->get();
        $filename = 'laporan-reservasi-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reservations) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No', 'Kode Booking', 'Nama Customer', 'Telepon', 'Tanggal', 'Jam', 'Tamu', 'DP', 'Status', 'Dibuat']);

            foreach ($reservations as $i => $r) {
                fputcsv($file, [
                    $i + 1,
                    $r->booking_code,
                    $r->customer_name,
                    $r->customer_phone,
                    \Carbon\Carbon::parse($r->reservation_date)->format('d/m/Y'),
                    $r->reservation_time,
                    $r->number_of_guests,
                    'Rp ' . number_format($r->down_payment ?? 0, 0, ',', '.'),
                    $r->status,
                    $r->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export CSV Tiket
     */
    public function exportTickets(Request $request)
    {
        $query = PoolTicket::query();
        if ($request->filled('start_date')) {
            $query->whereDate('visit_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('visit_date', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->get();
        $filename = 'laporan-tiket-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No', 'Kode Tiket', 'Nama Customer', 'Telepon', 'Tanggal Kunjungan', 'Jenis', 'Jumlah', 'Total', 'Status Bayar', 'Status Tiket']);

            foreach ($tickets as $i => $t) {
                fputcsv($file, [
                    $i + 1,
                    $t->ticket_code,
                    $t->customer_name,
                    $t->customer_phone,
                    \Carbon\Carbon::parse($t->visit_date)->format('d/m/Y'),
                    ucfirst($t->ticket_type),
                    $t->number_of_tickets,
                    'Rp ' . number_format($t->total_amount, 0, ',', '.'),
                    $t->payment_status == 'paid' ? 'Lunas' : ($t->payment_status == 'payment_verified' ? 'Menunggu Verifikasi' : 'Belum Bayar'),
                    $t->status == 'active' ? 'Aktif' : ($t->status == 'pending' ? 'Pending' : ucfirst($t->status)),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export CSV Pemasukan
     */
    public function exportIncome(Request $request)
    {
        // Kumpulkan data dari tiket yang sudah lunas
        $ticketQuery = PoolTicket::where('payment_status', 'paid');
        $reservationQuery = TableReservation::where('payment_status', 'paid');
        
        // Filter tanggal
        if ($request->filled('start_date')) {
            $startDate = $request->start_date;
            $ticketQuery->whereDate('paid_at', '>=', $startDate);
            $reservationQuery->whereDate('paid_at', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $endDate = $request->end_date;
            $ticketQuery->whereDate('paid_at', '<=', $endDate);
            $reservationQuery->whereDate('paid_at', '<=', $endDate);
        }
        
        $collections = collect();
        
        // Tambah data tiket
        if (!$request->filled('payment_type') || $request->payment_type == 'full_payment') {
            $tickets = $ticketQuery->get()->map(function($ticket) {
                return [
                    'payment_code' => $ticket->ticket_code,
                    'payment_type' => 'full_payment',
                    'payment_method' => $ticket->payment_method ?? 'transfer',
                    'amount' => $ticket->total_amount,
                    'status' => $ticket->payment_status,
                    'created_at' => $ticket->paid_at ?? $ticket->created_at,
                ];
            });
            $collections = $collections->concat($tickets);
        }
        
        // Tambah data reservasi
        if (!$request->filled('payment_type') || $request->payment_type == 'down_payment') {
            $reservations = $reservationQuery->get()->map(function($reservation) {
                return [
                    'payment_code' => $reservation->booking_code,
                    'payment_type' => 'down_payment',
                    'payment_method' => 'transfer',
                    'amount' => $reservation->down_payment ?? 50000,
                    'status' => $reservation->payment_status,
                    'created_at' => $reservation->paid_at ?? $reservation->created_at,
                ];
            });
            $collections = $collections->concat($reservations);
        }
        
        $collections = $collections->sortByDesc('created_at');
        $filename = 'laporan-pemasukan-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($collections) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No', 'Kode Transaksi', 'Tipe', 'Metode', 'Jumlah (Rp)', 'Status', 'Tanggal']);

            foreach ($collections as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item['payment_code'],
                    $item['payment_type'] == 'down_payment' ? 'DP Reservasi' : 'Full Payment Tiket',
                    $item['payment_method'],
                    number_format($item['amount'], 0, ',', '.'),
                    $item['status'] == 'paid' ? 'Lunas' : ($item['status'] == 'payment_verified' ? 'Menunggu Verifikasi' : 'Belum Bayar'),
                    is_string($item['created_at']) ? $item['created_at'] : $item['created_at']->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}