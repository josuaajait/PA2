@extends('layouts.admin')

@section('title', 'Laporan Pemasukan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Laporan Pemasukan</h6>
        <div>
            <form action="{{ route('admin.reports.export-income') }}" method="GET" class="d-inline">
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
                <label class="form-label">Tipe Transaksi</label>
                <select name="payment_type" class="form-control">
                    <option value="">Semua</option>
                    <option value="down_payment" {{ request('payment_type') == 'down_payment' ? 'selected' : '' }}>Down Payment (Reservasi)</option>
                    <option value="full_payment" {{ request('payment_type') == 'full_payment' ? 'selected' : '' }}>Full Payment (Tiket)</option>
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
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Total Pemasukan</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Dari Tiket Kolam</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['ticket_income'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="mb-0">Dari Reservasi (DP)</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['reservation_income'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-center">Perbandingan Pemasukan</h6>
                        <canvas id="incomeComparisonChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-center">Tren Pemasukan Harian</h6>
                        <canvas id="dailyIncomeChart" height="250"></canvas>
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
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Kode Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($payment->payment_type == 'down_payment')
                                <span class="badge bg-warning">DP Reservasi</span>
                            @else
                                <span class="badge bg-success">Full Payment Tiket</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->payment_method == 'transfer')
                                <i class="fas fa-university me-1"></i> Transfer
                            @elseif($payment->payment_method == 'credit_card')
                                <i class="fas fa-credit-card me-1"></i> Kartu Kredit
                            @else
                                <i class="fas fa-wallet me-1"></i> E-Wallet
                            @endif
                        </td>
                        <td class="fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>{!! $payment->payment_status_label !!}</td>
                        <td><small>{{ $payment->payment_code }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pemasukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form action="{{ route('admin.reports.export-income') }}" method="GET" class="d-inline">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="payment_type" value="{{ request('payment_type') }}">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </form>
        
        {{ $payments->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart perbandingan pemasukan
    const comparisonCtx = document.getElementById('incomeComparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'pie',
        data: {
            labels: ['Tiket Kolam', 'Reservasi (DP)'],
            datasets: [{
                data: [{{ $summary['ticket_income'] }}, {{ $summary['reservation_income'] }}],
                backgroundColor: ['#4caf50', '#ff9800']
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

    // Chart tren pemasukan harian
    const dailyCtx = document.getElementById('dailyIncomeChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels ?? []) !!},
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: {!! json_encode($dailyData ?? []) !!},
                borderColor: '#ff9800',
                backgroundColor: 'rgba(255, 152, 0, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush