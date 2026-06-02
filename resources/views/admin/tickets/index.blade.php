@extends('layouts.admin')

@section('title', 'Pool Tickets')

@push('styles')
<style>
    .report-page-header {
        background: linear-gradient(135deg, #1c3451 0%, #2a4a6b 100%);
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .report-page-header h6 {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .report-page-header .header-icon {
        width: 44px;
        height: 44px;
        background: rgba(193,160,103,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
    }

    .report-page-header .header-icon i {
        color: #c1a067;
        font-size: 18px;
    }

    .report-main-card {
        border: none;
        border-radius: 16px;
        border-top: 3px solid #c1a067 !important;
        box-shadow: 0 4px 20px rgba(28,52,81,0.08);
    }

    .report-main-card .card-body {
        padding: 24px;
    }

    .filter-section {
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border: 1px solid #e8edf2;
    }

    .filter-section .form-control,
    .filter-section .form-select {
        border-radius: 10px;
        border: 1px solid #dde2e8;
        font-size: 13px;
        padding: 8px 13px;
        color: #1c3451;
    }

    .filter-section .form-control:focus,
    .filter-section .form-select:focus {
        border-color: #c1a067;
        box-shadow: 0 0 0 3px rgba(193,160,103,0.15);
    }

    .report-table thead th {
        background: #1c3451;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 12px 14px;
        border: none;
    }

    .report-table thead th:first-child { border-radius: 10px 0 0 0; }
    .report-table thead th:last-child  { border-radius: 0 10px 0 0; }

    .report-table tbody tr { transition: background 0.15s; }
    .report-table tbody tr:hover { background: #f8fafc; }

    .report-table tbody td {
        font-size: 13px;
        color: #374151;
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .report-table tbody td.fw-bold { color: #1c3451; }

    .btn-view {
        background: #1c3451;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-view:hover { background: #c1a067; color: #fff; }

    .btn-verify {
        background: #15803d;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-verify:hover { background: #16a34a; color: #fff; }

    /* Custom badge colors */
    .badge-pending { background: #ffc107; color: #000; }
    .badge-paid { background: #198754; color: #fff; }
    .badge-unpaid { background: #dc3545; color: #fff; }
    .badge-verified { background: #0dcaf0; color: #000; }
    .badge-active { background: #198754; color: #fff; }
    .badge-used { background: #6c757d; color: #fff; }
    .badge-cancelled { background: #dc3545; color: #fff; }
</style>
@endpush

@section('content')

<div class="report-page-header">
    <div class="d-flex align-items-center">
        <div class="header-icon">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h6>Daftar Tiket Kolam</h6>
    </div>
</div>

<div class="card report-main-card">
    <div class="card-body">

        <div class="filter-section">
            <div class="row g-2">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter" onchange="filterTickets()">
                        <option value="">Semua Status Tiket</option>
                        <option value="pending">Pending</option>
                        <option value="active">Aktif</option>
                        <option value="used">Digunakan</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="paymentFilter" onchange="filterTickets()">
                        <option value="">Semua Status Bayar</option>
                        <option value="unpaid">Belum Bayar</option>
                        <option value="payment_verified">Menunggu Verifikasi</option>
                        <option value="paid">Lunas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchFilter" placeholder="Cari nama atau kode tiket..." onkeyup="filterTickets()">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table report-table" id="ticketsTable">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Customer</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jenis Tiket</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status Tiket</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="fw-bold">{{ $ticket->ticket_code }}</td>
                        <td>
                            {{ $ticket->customer_name }}<br>
                            <small class="text-muted">{{ $ticket->customer_phone }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($ticket->visit_date)->format('d M Y') }}<br>
                            <small class="text-muted">{{ $ticket->visit_time ?? 'Fleksibel' }}</small>
                        </td>
                        <td>
                            @if($ticket->ticket_type == 'adult')
                                <span class="badge bg-primary">Dewasa</span>
                            @elseif($ticket->ticket_type == 'child')
                                <span class="badge bg-info">Anak</span>
                            @else
                                <span class="badge bg-success">Keluarga</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $ticket->number_of_tickets }} tiket</td>
                        <td class="fw-bold">Rp {{ number_format($ticket->total_amount, 0, ',', '.') }}</td>
                        
                        {{-- Status Tiket --}}
                        <td>
                            @if($ticket->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($ticket->status == 'active')
                                <span class="badge badge-active">Aktif</span>
                            @elseif($ticket->status == 'used')
                                <span class="badge badge-used">Digunakan</span>
                            @elseif($ticket->status == 'cancelled')
                                <span class="badge badge-cancelled">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">{{ $ticket->status }}</span>
                            @endif
                        </td>
                        
                        {{-- Status Pembayaran --}}
                        <td>
                            @if($ticket->payment_status == 'unpaid')
                                <span class="badge badge-unpaid">Belum Bayar</span>
                            @elseif($ticket->payment_status == 'payment_verified')
                                <span class="badge badge-verified">Menunggu Verifikasi</span>
                            @elseif($ticket->payment_status == 'paid')
                                <span class="badge badge-paid">Lunas</span>
                            @else
                                <span class="badge bg-secondary">{{ $ticket->payment_status }}</span>
                            @endif
                        </td>
                        
                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                {{-- 🔥 TOMBOL VERIFIKASI: Hanya untuk payment_verified --}}
                                @if($ticket->payment_status == 'payment_verified')
                                    <button onclick="verifyTicket({{ $ticket->id }})" class="btn btn-verify" title="Verifikasi Pembayaran">
                                        <i class="fas fa-check-circle"></i> Verif
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-ticket-alt fa-3x mb-2 d-block" style="color: #c1a067; opacity: 0.4;"></i>
                            Belum ada data tiket kolam
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tickets->links() }}
        </div>

    </div>
</div>

@push('scripts')
<script>
    function verifyTicket(id) {
        if(confirm('Verifikasi pembayaran tiket ini? Tiket akan menjadi aktif setelah diverifikasi.')) {
            fetch(`/admin/tickets/${id}/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Gagal memverifikasi tiket');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }

    function filterTickets() {
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const payment = document.getElementById('paymentFilter').value.toLowerCase();
        const search = document.getElementById('searchFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#ticketsTable tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length < 9) return;
            
            let show = true;
            const rowStatus = row.cells[6]?.innerText.toLowerCase() || '';
            const rowPayment = row.cells[7]?.innerText.toLowerCase() || '';
            const rowText = row.innerText.toLowerCase();
            
            if (status && !rowStatus.includes(status)) show = false;
            if (payment && !rowPayment.includes(payment)) show = false;
            if (search && !rowText.includes(search)) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endpush

@endsection