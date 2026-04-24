@extends('layouts.admin')

@section('title', 'Laporan Tiket Kolam')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Laporan Penjualan Tiket Kolam</h6>
        <div>
            <form action="{{ route('admin.reports.export-tickets') }}" method="GET" class="d-inline">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </button>
            </form>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status Tiket</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Digunakan</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Total Tiket Terjual</h6>
                        <h3 class="mb-0">{{ $summary['total_tickets'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Total Transaksi</h6>
                        <h3 class="mb-0">{{ $summary['total_transactions'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Total Pendapatan</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Rata-rata per Tiket</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['average_price'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-center">Penjualan per Jenis Tiket</h6>
                        <canvas id="ticketTypeChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-center">Tren Penjualan</h6>
                        <canvas id="salesTrendChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Tiket</th>
                        <th>Customer</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jenis Tiket</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $index => $ticket)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $ticket->ticket_code }}</td>
                        <td>
                            {{ $ticket->customer_name }}<br>
                            <small class="text-muted">{{ $ticket->customer_phone }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ticket->visit_date)->format('d/m/Y') }}</td>
                        <td>
                            @if($ticket->ticket_type == 'adult')
                                <span class="badge bg-primary">Dewasa</span>
                            @elseif($ticket->ticket_type == 'child')
                                <span class="badge bg-info">Anak</span>
                            @else
                                <span class="badge bg-success">Keluarga</span>
                            @endif
                        </td>
                        <td>{{ $ticket->number_of_tickets }} tiket</td>
                        <td>Rp {{ number_format($ticket->total_amount, 0, ',', '.') }}</td>
                        <td>{!! $ticket->status_label !!}</td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data tiket</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $tickets->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart jenis tiket
    const typeCtx = document.getElementById('ticketTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($typeLabels ?? ['Dewasa', 'Anak', 'Keluarga']) !!},
            datasets: [{
                data: {!! json_encode($typeData ?? [0, 0, 0]) !!},
                backgroundColor: ['#667eea', '#4caf50', '#ff9800']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart tren penjualan
    const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels ?? []) !!},
            datasets: [{
                label: 'Jumlah Tiket Terjual',
                data: {!! json_encode($trendData ?? []) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>
@endpush