@extends('layouts.app')

@section('title', 'Beli Tiket Kolam')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i> Beli Tiket Kolam</h4>
                    <p class="mb-0 mt-2 opacity-75">Pilih tiket kolam renang Caldera</p>
                </div>
                <div class="card-body p-5">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="border rounded-3 p-3">
                                <i class="fas fa-user fa-3x text-primary mb-2"></i>
                                <h6>Dewasa</h6>
                                <p class="h5 text-primary">Rp 35.000</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded-3 p-3">
                                <i class="fas fa-child fa-3x text-success mb-2"></i>
                                <h6>Anak-anak</h6>
                                <p class="h5 text-success">Rp 25.000</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded-3 p-3">
                                <i class="fas fa-users fa-3x text-warning mb-2"></i>
                                <h6>Keluarga (4 org)</h6>
                                <p class="h5 text-warning">Rp 100.000</p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="ticketForm" method="POST" action="{{ route('reservation.ticket.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="customer_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <input type="date" name="visit_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis Tiket <span class="text-danger">*</span></label>
                                <select name="ticket_type" class="form-control" id="ticketType" required>
                                    <option value="adult">Dewasa - Rp 35.000</option>
                                    <option value="child">Anak-anak - Rp 25.000</option>
                                    <option value="family">Keluarga (4 org) - Rp 100.000</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah Tiket <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_tickets" id="numberOfTickets" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Harga</label>
                                <h4 class="text-primary" id="totalPrice">Rp 35.000</h4>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-lg px-5">
                                <i class="fas fa-shopping-cart me-2"></i> Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const prices = { adult: 35000, child: 25000, family: 100000 };
    
    document.getElementById('ticketType').addEventListener('change', updatePrice);
    document.getElementById('numberOfTickets').addEventListener('input', updatePrice);
    
    function updatePrice() {
        const type = document.getElementById('ticketType').value;
        const qty = document.getElementById('numberOfTickets').value;
        let price = prices[type];
        
        if (type === 'family') {
            price = 100000;
            document.getElementById('numberOfTickets').value = 1;
            document.getElementById('numberOfTickets').disabled = true;
        } else {
            document.getElementById('numberOfTickets').disabled = false;
        }
        
        const total = price * qty;
        document.getElementById('totalPrice').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection