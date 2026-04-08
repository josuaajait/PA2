@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Reservasi</h6>
        <div>
            <a href="{{ route('admin.reservations.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Export
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="statusFilter" onchange="filterReservations()">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control form-control-sm" id="dateFilter" placeholder="Filter Tanggal" onchange="filterReservations()">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="Cari nama atau kode booking..." onkeyup="filterReservations()">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="reservationsTable">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tamu</th>
                        <th>DP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td class="fw-bold">{{ $reservation->booking_code }}</td>
                        <td>
                            {{ $reservation->customer_name }}<br>
                            <small class="text-muted">{{ $reservation->customer_phone }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</td>
                        <td>{{ $reservation->number_of_guests }} orang</td>
                        <td>Rp {{ number_format($reservation->down_payment ?? 0, 0, ',', '.') }}</td>
                        <td>{!! $reservation->status_label !!}</td>
                        <td>
                            <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($reservation->status == 'pending')
                                <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi reservasi ini?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <button onclick="showCancelModal({{ $reservation->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data reservasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $reservations->links() }}
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Reservasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Pembatalan</label>
                        <textarea name="cancellation_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Batalkan Reservasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showCancelModal(id) {
        const form = document.getElementById('cancelForm');
        form.action = `/admin/reservations/${id}/cancel`;
        new bootstrap.Modal(document.getElementById('cancelModal')).show();
    }
    
    function filterReservations() {
        const status = document.getElementById('statusFilter').value;
        const date = document.getElementById('dateFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#reservationsTable tbody tr');
        
        rows.forEach(row => {
            let show = true;
            
            if (status) {
                const statusCell = row.cells[6]?.innerText.toLowerCase();
                if (!statusCell || !statusCell.includes(status)) show = false;
            }
            
            if (date && show) {
                const dateCell = row.cells[2]?.innerText;
                if (dateCell && !dateCell.includes(date)) show = false;
            }
            
            if (search && show) {
                const text = row.innerText.toLowerCase();
                if (!text.includes(search)) show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endpush
@endsection