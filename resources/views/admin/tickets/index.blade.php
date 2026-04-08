@extends('layouts.admin')

@section('title', 'Pool Tickets')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Tiket Kolam</h6>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="statusFilter" onchange="filterTickets()">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="used">Digunakan</option>
                    <option value="expired">Kadaluarsa</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="paymentFilter" onchange="filterTickets()">
                    <option value="">Semua Pembayaran</option>
                    <option value="paid">Lunas</option>
                    <option value="unpaid">Belum Bayar</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="Cari nama atau kode tiket..." onkeyup="filterTickets()">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="ticketsTable">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Customer</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jenis Tiket</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="fw-bold">{{ $ticket->ticket_code }}</td>
                        <td>
                            {{ $ticket->customer_name }}<br>
                            <small class="text-muted">{{ $ticket->customer_phone }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ticket->visit_date)->format('d M Y') }}<br>
                        <small>{{ $ticket->visit_time ?? 'Fleksibel' }}</small></td>
                        <td>
                            @if($ticket->ticket_type == 'adult')
                                <span class="badge bg-primary">Dewasa</span>
                            @elseif($ticket->ticket_type == 'child')
                                <span class="badge bg-info">Anak</span>
                            @else
                                <span class="badge bg-success">Keluarga</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $ticket->number_of_tickets }} tiket</td>
                        <td>Rp {{ number_format($ticket->total_amount, 0, ',', '.') }}</td>
                        <td>{!! $ticket->status_label !!}<br>
                        <small>{!! $ticket->payment_status_label !!}</small></td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($ticket->status == 'active' && $ticket->payment_status == 'paid')
                                <button onclick="verifyTicket({{ $ticket->id }})" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data tiket kolam</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $tickets->links() }}
    </div>
</div>

@push('scripts')
<script>
    function verifyTicket(id) {
        if(confirm('Tandai tiket ini sebagai sudah digunakan?')) {
            fetch(`/admin/tickets/${id}/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if(data.success) location.reload();
            });
        }
    }
    
    function filterTickets() {
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#ticketsTable tbody tr');
        
        rows.forEach(row => {
            let show = true;
            
            if (status) {
                const statusCell = row.cells[6]?.innerText.toLowerCase();
                if (!statusCell || !statusCell.includes(status)) show = false;
            }
            
            if (payment && show) {
                const paymentCell = row.cells[6]?.innerHTML.toLowerCase();
                if (!paymentCell || !paymentCell.includes(payment)) show = false;
            }
            
            if (search && show) {
                const text = row.innerText.toLowerCase();
                if (!text.includes(search)) show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endpush
@endsection