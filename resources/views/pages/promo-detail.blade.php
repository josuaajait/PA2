{{-- resources/views/branding/promo-detail.blade.php --}}

@extends('layouts.app')

@section('title', isset($promo->title) ? $promo->title . ' - Caldera' : 'Promo Detail - Caldera')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm promo-detail-card">
                @php
                    // Generate image URL
                    $imageUrl = null;
                    if (isset($promo->banner_image) && $promo->banner_image) {
                        if (str_starts_with($promo->banner_image, 'storage/')) {
                            $imageUrl = asset($promo->banner_image);
                        } elseif (str_contains($promo->banner_image, 'promos/')) {
                            $imageUrl = asset('storage/' . $promo->banner_image);
                        } elseif (filter_var($promo->banner_image, FILTER_VALIDATE_URL)) {
                            $imageUrl = $promo->banner_image;
                        } else {
                            $imageUrl = asset('storage/' . $promo->banner_image);
                        }
                    }
                    
                    $startDate = isset($promo->start_date) ? \Carbon\Carbon::parse($promo->start_date)->setTimezone('Asia/Jakarta') : null;
                    $endDate = isset($promo->end_date) ? \Carbon\Carbon::parse($promo->end_date)->setTimezone('Asia/Jakarta') : null;
                @endphp
                
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" 
                         class="card-img-top" 
                         alt="{{ $promo->title ?? 'Promo' }}"
                         style="height: 350px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='https://placehold.co/800x350/e8e0d0/c1a067?text=No+Image';">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 350px;">
                        <i class="fas fa-tag fa-4x" style="color: #c1a067;"></i>
                        <span class="ms-3 text-muted">{{ $promo->promo_code ?? 'Promo' }}</span>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h1 class="fw-bold mb-0" style="color: #1c3451;">{{ $promo->title ?? 'Promo' }}</h1>
                        <span class="badge-promo px-3 py-2">
                            @if(isset($promo->promo_type) && $promo->promo_type == 'ticket')
                                <i class="fas fa-ticket-alt me-1"></i> Tiket Kolam
                            @elseif(isset($promo->promo_type) && $promo->promo_type == 'menu')
                                <i class="fas fa-utensils me-1"></i> Menu Restoran
                            @elseif(isset($promo->promo_type) && $promo->promo_type == 'event')
                                <i class="fas fa-calendar-alt me-1"></i> Event
                            @elseif(isset($promo->promo_type) && $promo->promo_type == 'reservation')
                                <i class="fas fa-calendar-check me-1"></i> Reservasi
                            @else
                                Promo Spesial
                            @endif
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <div class="discount-badge d-inline-block mb-3">
                            @if(isset($promo->discount_type) && $promo->discount_type == 'percentage')
                                <span class="discount-percent">{{ $promo->discount_value ?? 0 }}% OFF</span>
                            @else
                                <span class="discount-amount">Rp {{ number_format($promo->discount_value ?? 0, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="promo-code-box mt-2">
                            <small class="text-muted">Kode Promo:</small>
                            <div class="code-wrapper mt-1">
                                <code class="d-inline-block px-4 py-2 rounded" style="background: #f0ebe0; color: #c1a067; font-size: 20px; letter-spacing: 2px;">
                                    {{ $promo->promo_code ?? '-' }}
                                </code>
                                <button class="btn btn-sm btn-copy ms-2" onclick="copyToClipboard('{{ $promo->promo_code ?? '' }}')" title="Salin kode">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold" style="color: #1c3451;">
                            <i class="fas fa-align-left me-2" style="color: #c1a067;"></i> Deskripsi Promo
                        </h5>
                        <p class="text-muted" style="line-height: 1.8;">{{ $promo->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt me-2" style="color: #c1a067;"></i>
                                    <small class="text-muted">Periode Promo</small>
                                </div>
                                <p class="fw-bold mb-0" style="color: #1c3451;">
                                    @if($startDate && $endDate)
                                        {{ $startDate->format('d F Y') }}<br>
                                        <span class="small">s/d</span><br>
                                        {{ $endDate->format('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clipboard-list me-2" style="color: #c1a067;"></i>
                                    <small class="text-muted">Syarat & Ketentuan</small>
                                </div>
                                <ul class="mb-0 text-muted small" style="padding-left: 18px;">
                                    @if(isset($promo->min_purchase) && $promo->min_purchase)
                                        <li>Minimal belanja Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</li>
                                    @else
                                        <li>Tanpa minimal pembelian</li>
                                    @endif
                                    @if(isset($promo->max_discount) && $promo->max_discount)
                                        <li>Maksimal diskon Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</li>
                                    @endif
                                    @if(isset($promo->max_usage) && $promo->max_usage)
                                        <li>Maksimal {{ $promo->max_usage }} kali penggunaan</li>
                                    @endif
                                    <li>Promo tidak dapat digabung dengan promo lain</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('reservation.ticket') }}" class="btn btn-promo btn-lg px-5">
                            <i class="fas fa-ticket-alt me-2"></i> Gunakan Promo Sekarang
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Kode promo ' + text + ' telah disalin!');
        }, function() {
            alert('Gagal menyalin kode');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .badge-promo {
        background: rgba(193,160,103,0.15);
        color: #c1a067;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .discount-badge {
        background: linear-gradient(135deg, #1c3451, #01516e);
        padding: 8px 20px;
        border-radius: 30px;
    }
    
    .discount-percent, .discount-amount {
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .promo-code-box {
        margin-top: 10px;
    }
    
    .promo-code-box code {
        font-size: 20px;
        letter-spacing: 2px;
        font-weight: 700;
    }
    
    .btn-copy {
        background: #e8e0d0;
        color: #c1a067;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .btn-copy:hover {
        background: #c1a067;
        color: white;
    }
    
    .btn-promo {
        background: linear-gradient(135deg, #1c3451, #01516e);
        color: white;
        border-radius: 40px;
        padding: 14px 40px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
    }
    
    .btn-promo:hover {
        background: linear-gradient(135deg, #c1a067, #a8894f);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(193,160,103,0.3);
    }
    
    .info-card {
        border: 1px solid #e8e0d0;
        transition: all 0.2s;
        height: 100%;
    }
    
    .info-card:hover {
        border-color: #c1a067;
        transform: translateY(-3px);
    }
    
    .promo-detail-card {
        border-radius: 24px !important;
        overflow: hidden;
    }
    
    /* Dark Mode */
    body.dark-mode .info-card {
        background: #1e1e2a !important;
        border-color: #2d2d3a;
    }
    
    body.dark-mode .info-card:hover {
        border-color: #c1a067;
    }
    
    body.dark-mode .btn-copy {
        background: #2d2d3a;
        color: #c1a067;
    }
    
    body.dark-mode .btn-copy:hover {
        background: #c1a067;
        color: #1c3451;
    }
    
    body.dark-mode .promo-code-box code {
        background: #2d2d3a !important;
        color: #c1a067 !important;
    }
</style>
@endpush