@extends('layouts.app')

@section('title', 'Reservasi Meja')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Reservasi Meja</h4>
                    <p class="mb-0 mt-2 opacity-75">Isi formulir berikut untuk reservasi meja</p>
                </div>
                <div class="card-body p-5">
                    <form id="reservationForm" method="POST" action="{{ route('reservation.table.store') }}">
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
                                <label class="form-label">Jumlah Tamu <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_guests" class="form-control" min="1" max="20" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Reservasi <span class="text-danger">*</span></label>
                                <input type="date" name="reservation_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Reservasi <span class="text-danger">*</span></label>
                                <select name="reservation_time" class="form-control" required>
                                    <option value="">Pilih Jam</option>
                                    <option value="11:00:00">11:00</option>
                                    <option value="12:00:00">12:00</option>
                                    <option value="13:00:00">13:00</option>
                                    <option value="14:00:00">14:00</option>
                                    <option value="17:00:00">17:00</option>
                                    <option value="18:00:00">18:00</option>
                                    <option value="19:00:00">19:00</option>
                                    <option value="20:00:00">20:00</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Permintaan Khusus</label>
                                <textarea name="special_requests" class="form-control" rows="3" placeholder="Contoh: kursi bayi, area non-rokok, dll"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    DP 30% dari estimasi belanja akan dikonfirmasi setelah reservasi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-lg px-5">
                                <i class="fas fa-check-circle me-2"></i> Reservasi Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection