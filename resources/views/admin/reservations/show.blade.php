@extends('layouts.admin')

@section('title', 'Detail Reservasi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Detail Reservasi: {{ $reservation->booking_code }}</h6>
        <div>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Informasi Customer</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 120px;">Nama</th>
                        <td>{{ $reservation->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $reservation->customer_email }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $reservation->customer_phone }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Informasi Reservasi</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 120px;">Kode Booking</th>
                        <td>{{ $reservation->booking_code }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Jam</th>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Tamu</th>
                        <td>{{ $reservation->number_of_guests }} orang</td>
                    </tr>
                    <tr>
                        <th>DP</th>
                        <td>Rp {{ number_format($reservation->down_payment ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $reservation->status_label !!}</td>
                    </tr>
                    @if($reservation->special_requests)
                    <tr>
                        <th>Request Khusus</th>
                        <td>{{ $reservation->special_requests }}</td>
                    </tr>
                    @endif
                    @if($reservation->cancellation_reason)
                    <tr>
                        <th>Alasan Batal</th>
                        <td class="text-danger">{{ $reservation->cancellation_reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        @if($reservation->status == 'pending')
        <div class="text-end mt-3">
            <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi reservasi ini?')">
                    <i class="fas fa-check me-1"></i> Konfirmasi Reservasi
                </button>
            </form>
            <button onclick="showCancelModal({{ $reservation->id }})" class="btn btn-warning">
                <i class="fas fa-times me-1"></i> Batalkan Reservasi
            </button>
        </div>
        @endif
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
</script>
@endpush
@endsection