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
    ];

    $typeBadges = [
        'success' => 'bg-success',
        'info' => 'bg-info',
        'warning' => 'bg-warning text-dark',
        'danger' => 'bg-danger',
        'primary' => 'bg-primary',
    ];
@endphp
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
                            <span class="badge rounded-pill bg-dark-subtle text-dark notifications-count">{{ $notifications->total() }} notifikasi</span>
                            @if($unreadCount > 0)
                                <div class="small text-muted mt-1">{{ $unreadCount }} belum dibaca</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    @forelse($notifications as $notif)
                    <div class="notification-item card border-0 mb-3 {{ $notif->read_at ? '' : 'unread' }}" data-id="{{ $notif->id }}">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="notification-icon rounded-circle d-flex align-items-center justify-content-center notification-icon--{{ $notif->color }}">
                                        <i class="fas {{ $notif->icon }} fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                        <div>
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                <h6 class="fw-bold mb-0 notification-title">{{ $notif->title }}</h6>
                                                @if(!$notif->read_at)
                                                    <span class="badge bg-danger notification-new-badge">BARU</span>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge {{ $typeBadges[$notif->color] ?? 'bg-secondary' }} notification-type-badge">
                                                    {{ $typeLabels[$notif->type] ?? ucfirst($notif->type) }}
                                                </span>
                                                @if($notif->ticket_code)
                                                    <span class="badge text-bg-light notification-meta-badge">Tiket: {{ $notif->ticket_code }}</span>
                                                @endif
                                                @if($notif->booking_code)
                                                    <span class="badge text-bg-light notification-meta-badge">Booking: {{ $notif->booking_code }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted text-nowrap">{{ $notif->created_at->diffForHumans() }}</small>
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 empty-state">
                        <i class="fas fa-bell-slash fa-4x mb-3 empty-state-icon"></i>
                        <p class="text-muted">Belum ada notifikasi</p>
                    </div>
                    @endforelse
                </div>
                @if($notifications->hasPages())
                <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                @endif
            </div>
    </div>
</div>

<style>
.notifications-card {
    border-radius: 20px;
    overflow: hidden;
}

.notifications-title {
    color: #1c3451;
}

.notifications-icon {
    color: #c1a067;
}

.notifications-count {
    background: #f0ebe0;
    color: #1c3451;
    font-weight: 600;
}

.notification-item {
    border-radius: 16px;
    background: #fff;
.notification-item:hover {
    transform: translateY(-1px);
}

.notification-item.unread {
    background: #fdfbf7;
    border: 1px solid #eadfca;
}

.notification-icon {
    width: 48px;
    height: 48px;
    background: #f0ebe0;
}

.notification-icon--success {
    background: #e8f5e9;
    color: #2e7d32;
}

.notification-icon--info {
    background: #e3f2fd;
    color: #1565c0;
}

.notification-icon--warning {
    background: #fff8e1;
    color: #f9a825;
}

.notification-icon--danger {
    background: #ffebee;
    color: #c62828;
}

.notification-icon--primary {
    background: #f0ebe0;
    color: #c1a067;
}

.notification-title {
    color: #1c3451;
}

.notification-body {
    line-height: 1.6;
}

.notification-new-badge,
.notification-type-badge,
.notification-meta-badge {
    font-size: 11px;
}

.btn-outline-caldera {
    border: 1px solid #c1a067;
    color: #c1a067;
    background: transparent;
    border-radius: 8px;
    font-size: 12px;
    padding: 4px 12px;
    transition: all 0.2s;
}
.btn-outline-caldera:hover {
    background: #c1a067;
    color: white;
}

.empty-state-icon {
    color: #c1a067;
    opacity: 0.4;
}

body.dark-mode .notification-item:hover {
    background: #2d2d3a;
}
body.dark-mode .notification-item.bg-light {
    background: #2a2a3a !important;
}

body.dark-mode .notifications-title,
body.dark-mode .notification-title {
    color: #dce8f0;
}

body.dark-mode .notification-item {
    background: #1e1e2a;
    box-shadow: none;
    border-color: #2d2d3a;
}

body.dark-mode .notification-item.unread {
    background: #242436;
    border-color: #3a3a4a;
}

body.dark-mode .notifications-count {
    background: #2d2d3a;
    color: #dce8f0;
}

body.dark-mode .notification-meta-badge {
    background: #2d2d3a !important;
    color: #dce8f0 !important;
}
</style>
@endsection