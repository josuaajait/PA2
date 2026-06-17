{{-- resources/views/customer/notifications.blade.php --}}
@extends('layouts.app')

@section('title', 'Notifikasi - Caldera')

@section('content')
@php
    $typeLabels = [
        'general' => 'Umum',
        'purchased' => 'Pembelian',
        'paid' => 'Pembayaran',
        'used' => 'Tiket Digunakan',
        'payment_verified_by_admin' => 'Verifikasi Pembayaran',
        'reservation_confirmed' => 'Reservasi Dikonfirmasi',
        'reservation_cancelled' => 'Reservasi Dibatalkan',
    ];

    $typeBadges = [
        'success' => 'bg-success',
        'info' => 'bg-info',
        'warning' => 'bg-warning text-dark',
        'danger' => 'bg-danger',
        'primary' => 'bg-primary',
    ];

    $typeIcons = [
        'general' => 'fa-info-circle',
        'purchased' => 'fa-shopping-cart',
        'paid' => 'fa-credit-card',
        'used' => 'fa-check-circle',
        'payment_verified_by_admin' => 'fa-check-double',
        'reservation_confirmed' => 'fa-calendar-check',
        'reservation_cancelled' => 'fa-calendar-times',
    ];
@endphp

{{-- ✅ FIX: Tambahkan class "notification-page" di sini --}}
<div class="notification-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm notifications-card">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h4 class="mb-1 fw-bold notifications-title">
                                    <i class="fas fa-bell me-2 notifications-icon"></i> Notifikasi Saya
                                </h4>
                                <p class="text-muted small mb-0">Menampilkan notifikasi terbaru untuk akun Anda.</p>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill notifications-count">
                                    <i class="fas fa-inbox me-1"></i> {{ $notifications->total() }} notifikasi
                                </span>
                                @if($unreadCount > 0)
                                    <div class="small text-muted mt-1">
                                        <span class="badge bg-danger">{{ $unreadCount }}</span> belum dibaca
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body px-4 py-3">
                        <!-- Tombol Mark All Read -->
                        @if($unreadCount > 0)
                        <div class="d-flex justify-content-end mb-3">
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                        @endif
                        
                        @forelse($notifications as $notif)
                        <div class="notification-item card border-0 mb-3 {{ $notif->read_at ? '' : 'unread' }}" data-id="{{ $notif->id }}">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="notification-icon rounded-circle d-flex align-items-center justify-content-center notification-icon--{{ $notif->color }}">
                                            <i class="fas {{ $typeIcons[$notif->type] ?? 'fa-bell' }} fa-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                            <div>
                                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                    <h6 class="fw-bold mb-0 notification-title">{{ $notif->title }}</h6>
                                                    @if(!$notif->read_at)
                                                        <span class="badge bg-danger notification-new-badge">
                                                            <i class="fas fa-circle me-1" style="font-size: 6px;"></i> BARU
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge {{ $typeBadges[$notif->color] ?? 'bg-secondary' }} notification-type-badge">
                                                        {{ $typeLabels[$notif->type] ?? ucfirst($notif->type) }}
                                                    </span>
                                                    @if($notif->ticket_code)
                                                        <span class="badge text-bg-light notification-meta-badge">
                                                            <i class="fas fa-ticket-alt me-1"></i> {{ $notif->ticket_code }}
                                                        </span>
                                                    @endif
                                                    @if($notif->booking_code)
                                                        <span class="badge text-bg-light notification-meta-badge">
                                                            <i class="fas fa-calendar-alt me-1"></i> {{ $notif->booking_code }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <small class="text-muted text-nowrap" title="{{ $notif->created_at->format('d M Y H:i') }}">
                                                <i class="far fa-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>

                                        <p class="text-muted small mb-3 notification-body">{{ $notif->body }}</p>

                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            @if($notif->url)
                                                <a href="{{ $notif->url }}" class="btn btn-sm btn-outline-caldera">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>
                                            @endif

                                            @if(!$notif->read_at)
                                                <form action="{{ route('notifications.mark-read', $notif->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-muted text-decoration-none px-0">
                                                        <i class="fas fa-check-circle me-1"></i> Tandai Dibaca
                                                    </button>
                                                </form>
                                            @else
                                                <small class="text-muted">
                                                    <i class="fas fa-check-circle me-1" style="color: #2e7d32;"></i> 
                                                    Dibaca {{ $notif->read_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-bell-slash fa-4x mb-3 empty-state-icon"></i>
                            </div>
                            <h5 class="fw-bold" style="color: #1c3451;">Tidak Ada Notifikasi</h5>
                            <p class="text-muted">Anda belum memiliki notifikasi apapun saat ini.</p>
                            <a href="{{ route('home') }}" class="btn btn-caldera mt-3">
                                <i class="fas fa-home me-2"></i> Kembali ke Beranda
                            </a>
                        </div>
                        @endforelse
                    </div>
                    
                    @if($notifications->hasPages())
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="text-muted small">
                                Menampilkan {{ $notifications->firstItem() ?? 0 }} - {{ $notifications->lastItem() ?? 0 }} 
                                dari {{ $notifications->total() }} notifikasi
                            </div>
                            <div class="pagination-wrapper">
                                {{ $notifications->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- ✅ FIX: @endsection wajib ada di sini, SEBELUM @push --}}

@push('styles')
<style>
/* ========================================== */
/* SEMUA CSS DISCOPE DENGAN .notification-page */
/* ========================================== */

.notification-page .notifications-card {
    border-radius: 20px;
    overflow: hidden;
    border-top: 3px solid #c1a067 !important;
}

.notification-page .notifications-title {
    color: #1c3451;
    font-family: 'Playfair Display', serif;
}

.notification-page .notifications-icon {
    color: #c1a067;
}

.notification-page .notifications-count {
    background: #f0ebe0;
    color: #1c3451;
    font-weight: 600;
    padding: 6px 14px;
}

.notification-page .notification-item {
    border-radius: 16px;
    background: #fff;
    transition: all 0.25s ease;
    box-shadow: 0 1px 3px rgba(28, 52, 81, 0.06);
    border: 1px solid transparent;
}

.notification-page .notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 52, 81, 0.1);
    border-color: #c1a067;
}

.notification-page .notification-item.unread {
    background: #fdfbf7;
    border-color: #eadfca;
    border-left: 4px solid #c1a067;
}

.notification-page .notification-icon {
    width: 48px;
    height: 48px;
    background: #f0ebe0;
    flex-shrink: 0;
}

.notification-page .notification-icon--success {
    background: #e8f5e9;
    color: #2e7d32;
}

.notification-page .notification-icon--info {
    background: #e3f2fd;
    color: #1565c0;
}

.notification-page .notification-icon--warning {
    background: #fff8e1;
    color: #f9a825;
}

.notification-page .notification-icon--danger {
    background: #ffebee;
    color: #c62828;
}

.notification-page .notification-icon--primary {
    background: #f0ebe0;
    color: #c1a067;
}

.notification-page .notification-title {
    color: #1c3451;
}

.notification-page .notification-body {
    line-height: 1.6;
    color: #6c757d;
}

.notification-page .notification-new-badge {
    font-size: 10px;
    padding: 2px 10px;
    letter-spacing: 0.5px;
    animation: notification-page-pulse-badge 2s infinite;
}

@keyframes notification-page-pulse-badge {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.notification-page .notification-type-badge,
.notification-page .notification-meta-badge {
    font-size: 11px;
    padding: 4px 10px;
    font-weight: 500;
}

.notification-page .btn-outline-caldera {
    border: 1.5px solid #c1a067;
    color: #c1a067;
    background: transparent;
    border-radius: 8px;
    font-size: 12px;
    padding: 4px 14px;
    transition: all 0.2s;
    font-weight: 600;
}

.notification-page .btn-outline-caldera:hover {
    background: #c1a067;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(193,160,103,0.3);
}

.notification-page .btn-caldera {
    background: linear-gradient(135deg, #1c3451, #01516e);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.2s;
}

.notification-page .btn-caldera:hover {
    background: linear-gradient(135deg, #c1a067, #a8894f);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(193,160,103,0.3);
}

.notification-page .empty-state-icon {
    color: #c1a067;
    opacity: 0.4;
}

/* Pagination */
.notification-page .pagination-wrapper {
    display: flex;
    justify-content: flex-end;
}

.notification-page .pagination-wrapper .pagination {
    margin-bottom: 0;
    gap: 4px;
}

.notification-page .pagination-wrapper .page-link {
    border: 1.5px solid #e8e0d0;
    border-radius: 10px !important;
    padding: 8px 16px;
    color: #1c3451;
    background: white;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s;
    margin: 0 2px;
}

.notification-page .pagination-wrapper .page-link:hover {
    background: #1c3451;
    color: white;
    border-color: #1c3451;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(28,52,81,0.2);
    z-index: 2;
}

.notification-page .pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #1c3451, #01516e);
    color: white;
    border-color: #1c3451;
    box-shadow: 0 4px 12px rgba(28,52,81,0.25);
}

.notification-page .pagination-wrapper .page-item.disabled .page-link {
    background: #f8f6f2;
    color: #b0a890;
    border-color: #f0ebe0;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

.notification-page .pagination-wrapper .page-item:first-child .page-link {
    border-radius: 10px !important;
}

.notification-page .pagination-wrapper .page-item:last-child .page-link {
    border-radius: 10px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .notification-page .notification-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .notification-page .notification-item .flex-shrink-0 {
        margin-bottom: 8px;
    }
    
    .notification-page .notification-item .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        width: 100%;
    }
    
    .notification-page .notification-item .text-nowrap {
        white-space: normal !important;
        margin-top: 4px;
    }
    
    .notification-page .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .notification-page .card-header .text-end {
        text-align: left !important;
        width: 100%;
    }
    
    .notification-page .pagination-wrapper {
        justify-content: center !important;
        width: 100%;
    }
    
    .notification-page .pagination-wrapper .page-link {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .notification-page .card-footer .d-flex {
        flex-direction: column;
        align-items: center !important;
        gap: 12px;
    }
    
    .notification-page .card-footer .text-muted {
        text-align: center;
    }
}

/* Dark Mode */
body.dark-mode .notification-page .notification-item {
    background: #1e1e2a;
    box-shadow: none;
    border-color: transparent;
}

body.dark-mode .notification-page .notification-item:hover {
    background: #2d2d3a;
    border-color: #c1a067;
}

body.dark-mode .notification-page .notification-item.unread {
    background: #242436;
    border-color: #3a3a4a;
    border-left-color: #c1a067;
}

body.dark-mode .notification-page .notifications-title,
body.dark-mode .notification-page .notification-title {
    color: #dce8f0;
}

body.dark-mode .notification-page .notifications-count {
    background: #2d2d3a;
    color: #dce8f0;
}

body.dark-mode .notification-page .notification-meta-badge {
    background: #2d2d3a !important;
    color: #dce8f0 !important;
}

body.dark-mode .notification-page .notification-body {
    color: #b0b0b0;
}

body.dark-mode .notification-page .btn-outline-caldera {
    border-color: #c1a067;
    color: #c1a067;
}

body.dark-mode .notification-page .btn-outline-caldera:hover {
    background: #c1a067;
    color: #1c3451;
}

body.dark-mode .notification-page .btn-caldera {
    background: linear-gradient(135deg, #c1a067, #a8894f);
}

body.dark-mode .notification-page .btn-caldera:hover {
    background: linear-gradient(135deg, #a8894f, #8b6d3f);
}

body.dark-mode .notification-page .notification-icon--success {
    background: #1a2a1a;
    color: #81c784;
}

body.dark-mode .notification-page .notification-icon--info {
    background: #1a2a3a;
    color: #64b5f6;
}

body.dark-mode .notification-page .notification-icon--warning {
    background: #2a2a1a;
    color: #ffb74d;
}

body.dark-mode .notification-page .notification-icon--danger {
    background: #2a1a1a;
    color: #ef9a9a;
}

body.dark-mode .notification-page .notification-icon--primary {
    background: #2d2d3a;
    color: #c1a067;
}

body.dark-mode .notification-page .pagination-wrapper .page-link {
    background: #1e1e2a;
    border-color: #2d2d3a;
    color: #dce8f0;
}

body.dark-mode .notification-page .pagination-wrapper .page-link:hover {
    background: #c1a067;
    color: #1c3451;
    border-color: #c1a067;
}

body.dark-mode .notification-page .pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #c1a067, #a8894f);
    color: #1c3451;
    border-color: #c1a067;
}

body.dark-mode .notification-page .pagination-wrapper .page-item.disabled .page-link {
    background: #2d2d3a;
    color: #5a5a6a;
    border-color: #2d2d3a;
}
</style>
@endpush