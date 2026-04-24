@extends('layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=667eea&color=fff" class="rounded-circle mb-3" width="80">
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                    <hr>
                    <a href="{{ route('customer.reservations') }}" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="fas fa-calendar-alt me-2"></i> My Reservations
                    </a>
                    <a href="{{ route('customer.tickets') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-ticket-alt me-2"></i> My Tickets
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Daftar Reservasi Saya</h5>
                </div>
                <div class="card-body">
                    @forelse($reservations as $reservation)
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <small class="text-muted">Kode Booking</small>
                                <p class="fw-bold mb-0">{{ $reservation->booking_code }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Tanggal & Jam</small>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</p>
                                <small>{{ $reservation->reservation_time }}</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Tamu</small>
                                <p class="mb-0">{{ $reservation->number_of_guests }} orang</p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Status</small>
                                <p class="mb-0">{!! $reservation->status_label !!}</p>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="{{ route('reservation.table.view', $reservation->booking_code) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                        <p>Belum ada reservasi</p>
                        <a href="{{ route('reservation.table') }}" class="btn btn-primary">Reservasi Sekarang</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection