@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-ticket-alt fa-4x text-primary"></i>
                </div>
                <h5 class="fw-bold">Laporan Tiket Kolam</h5>
                <p class="text-muted">Lihat laporan penjualan tiket kolam</p>
                <a href="{{ route('admin.reports.tickets') }}" class="btn btn-primary">
                    <i class="fas fa-chart-line me-1"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-4x text-success"></i>
                </div>
                <h5 class="fw-bold">Laporan Reservasi</h5>
                <p class="text-muted">Lihat laporan reservasi meja</p>
                <a href="{{ route('admin.reports.reservations') }}" class="btn btn-success">
                    <i class="fas fa-chart-line me-1"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-money-bill-wave fa-4x text-warning"></i>
                </div>
                <h5 class="fw-bold">Laporan Pemasukan</h5>
                <p class="text-muted">Lihat laporan pendapatan</p>
                <a href="{{ route('admin.reports.income') }}" class="btn btn-warning">
                    <i class="fas fa-chart-line me-1"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection