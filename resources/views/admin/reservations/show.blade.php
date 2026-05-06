@extends('layouts.admin')

@section('title', 'Detail Reservasi')

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

    .booking-code-badge {
        background: rgba(193,160,103,0.2);
        color: #c1a067;
        border: 1px solid rgba(193,160,103,0.4);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        padding: 5px 14px;
        letter-spacing: 0.5px;
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }

    .report-main-card {
        border: none;
        border-radius: 16px;
        border-top: 3px solid #c1a067 !important;
        box-shadow: 0 4px 20px rgba(28,52,81,0.08);
    }

    .report-main-card .card-body {
        padding: 28px;
    }

    .detail-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #1c3451;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        border-bottom: 2px solid #c1a067;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-section-title i { color: #c1a067; font-size: 14px; }

    .detail-table { width: 100%; }

    .detail-table tr td:first-child {
        width: 160px;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 9px 0;
        vertical-align: middle;
    }

    .detail-table tr td:last-child {
        font-size: 14px;
        color: #1c3451;
        font-weight: 500;
        padding: 9px 0;
        vertical-align: middle;
    }

    .detail-table tr { border-bottom: 1px solid #f3f4f6; }
    .detail-table tr:last-child { border-bottom: none; }

    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, #c1a067 0%, transparent 100%);
        margin: 24px 0;
        border: none;
    }

    .btn-confirm-main {
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 24px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(21,128,61,0.3);
    }

    .btn-confirm-main:hover {
        background: linear-gradient(135deg, #166534, #15803d);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-cancel-main {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 24px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(217,119,6,0.3);
    }

    .btn-cancel-main:hover {
        background: linear-gradient(135deg, #b45309, #92400e);
        color: #fff;
        transform: translateY(-1px);
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 16px;
        border-top: 3px solid #c1a067;
        box-shadow: 0 8px 32px rgba(28,52,81,0.15);
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 18px 24px;
    }

    .modal-header .modal-title {
        font-size: 15px;
        font-weight: 700;
        color: #1c3451;
    }

    .modal-body { padding: 20px 24px; }
    .modal-footer { border-top: 1px solid #f0f0f0; padding: 16px 24px; }

    .modal-body .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #1c3451;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .modal-body .form-control {
        border-radius: 10px;
        border: 1px solid #dde2e8;
        font-size: 13px;
        color: #1c3451;
    }

    .modal-body .form-control:focus {
        border-color: #c1a067;
        box-shadow: 0 0 0 3px rgba(193,160,103,0.15);
    }

    .btn-modal-close {
        background: #f1f5f9;
        color: #1c3451;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
    }

    .btn-modal-close:hover { background: #e2e8f0; color: #1c3451; }

    .btn-modal-danger {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        transition: all 0.2s;
    }

    .btn-modal-danger:hover { background: linear-gradient(135deg, #b91c1c, #991b1b); color: #fff; }
</style>
@endpush

@section('content')

<div class="report-page-header">
    <div class="d-flex align-items-center gap-3">
        <div class="header-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div>
            <h6>Detail Reservasi</h6>
        </div>
        <span class="booking-code-badge">{{ $reservation->booking_code }}</span>
    </div>
    <a href="{{ route('admin.reservations.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card report-main-card">
    <div class="card-body">

        <div class="row g-4">
            {{-- Informasi Customer --}}
            <div class="col-md-6">
                <div class="detail-section-title">
                    <i class="fas fa-user"></i> Informasi Customer
                </div>
                <table class="detail-table">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $reservation->customer_name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $reservation->customer_email }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>{{ $reservation->customer_phone }}</td>
                    </tr>
                </table>
            </div>

            {{-- Informasi Reservasi --}}
            <div class="col-md-6">
                <div class="detail-section-title">
                    <i class="fas fa-info-circle"></i> Informasi Reservasi
                </div>
                <table class="detail-table">
                    <tr>
                        <td>Kode Booking</td>
                        <td><span class="fw-bold" style="color:#1c3451;">{{ $reservation->booking_code }}</span></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Tamu</td>
                        <td>{{ $reservation->number_of_guests }} orang</td>
                    </tr>
                    <tr>
                        <td>Down Payment</td>
                        <td><span class="fw-bold" style="color:#c1a067; font-size:15px;">Rp {{ number_format($reservation->down_payment ?? 0, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{!! $reservation->status_label !!}</td>
                    </tr>
                    @if($reservation->special_requests)
                    <tr>
                        <td>Request Khusus</td>
                        <td>{{ $reservation->special_requests }}</td>
                    </tr>
                    @endif
                    @if($reservation->cancellation_reason)
                    <tr>
                        <td>Alasan Batal</td>
                        <td class="text-danger">{{ $reservation->cancellation_reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($reservation->status == 'pending')
        <hr class="section-divider">
        <div class="text-end d-flex justify-content-end gap-2">
            <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-confirm-main" onclick="return confirm('Konfirmasi reservasi ini?')">
                    <i class="fas fa-check me-1"></i> Konfirmasi Reservasi
                </button>
            </form>
            <button onclick="showCancelModal({{ $reservation->id }})" class="btn-cancel-main">
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
                    <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-modal-danger">Batalkan Reservasi</button>
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