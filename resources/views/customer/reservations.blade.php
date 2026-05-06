@extends('layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1c3451&color=c1a067" class="rounded-circle mb-3" width="80">
                    <h5 class="fw-bold" style="color: #1c3451;">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                    <hr>
                    <a href="{{ route('customer.reservations') }}" class="btn btn-sm w-100 mb-2" style="background-color: #1c3451; color: white; border-radius: 8px;">
                        <i class="fas fa-calendar-alt me-2"></i> My Reservations
                    </a>
                    <a href="{{ route('customer.tickets') }}" class="btn btn-sm w-100" style="border: 1px solid #1c3451; color: #1c3451; border-radius: 8px;">
                        <i class="fas fa-ticket-alt me-2"></i> My Tickets
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom" style="padding: 16px 20px;">
                    <h5 class="mb-0 fw-bold" style="color: #1c3451; font-family: 'Playfair Display', serif;">
                        <i class="fas fa-calendar-alt me-2" style="color: #c1a067;"></i>Daftar Reservasi Saya
                    </h5>
                </div>
                <div class="card-body p-3">
                    @forelse($reservations as $reservation)
                    <div class="reservation-item border rounded-3 p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <small class="text-muted">Kode Booking</small>
                                <p class="fw-bold mb-0" style="color: #1c3451;">{{ $reservation->booking_code }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Tanggal & Jam</small>
                                <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</p>
                                <small class="text-muted">{{ $reservation->reservation_time }}</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Tamu</small>
                                <p class="mb-0 fw-semibold">{{ $reservation->number_of_guests }} orang</p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Status</small>
                                <p class="mb-0">{!! $reservation->status_label !!}</p>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="{{ route('reservation.table.view', $reservation->booking_code) }}" class="btn btn-sm" style="background-color: #1c3451; color: white; border-radius: 8px;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-4x mb-3" style="color: #c1a067; opacity: 0.5;"></i>
                        <p class="text-muted">Belum ada reservasi</p>
                        <a href="{{ route('reservation.table') }}" class="btn" style="background-color: #1c3451; color: white; border-radius: 8px; padding: 8px 24px;">
                            Reservasi Sekarang
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');

    .card {
        border-radius: 16px !important;
    }

    .reservation-item {
        border-color: #e8e0d0 !important;
        background: #fdfcfa;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .reservation-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(28,52,81,0.08);
        border-color: #c1a067 !important;
    }

    .card-header {
        border-bottom: 2px solid #f0ebe0 !important;
        background: white !important;
        border-radius: 16px 16px 0 0 !important;
    }
</style>
@endpush