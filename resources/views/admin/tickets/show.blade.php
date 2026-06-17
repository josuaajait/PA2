@extends('layouts.admin')

@section('title', 'Detail Tiket - ' . $ticket->ticket_code)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-ticket-alt me-2" style="color: #c1a067;"></i>
            Detail Tiket: {{ $ticket->ticket_code }}
        </h6>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        <div class="row">
            <!-- Kolom Kiri: Informasi Customer -->
            <div class="col-md-6">
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-user me-2" style="color: #c1a067;"></i>
                            Informasi Customer
                        </h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th style="width: 100px;">Nama</th>
                                <td><strong>{{ $ticket->customer_name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $ticket->customer_email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $ticket->customer_phone }}</td>
                            </tr>
                            <tr>
                                <th>User ID</th>
                                <td>{{ $ticket->user_id ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Kolom Kanan: Informasi Tiket -->
            <div class="col-md-6">
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2" style="color: #c1a067;"></i>
                            Informasi Tiket
                        </h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th style="width: 120px;">Kode Tiket</th>
                                <td><code>{{ $ticket->ticket_code }}</code></td>
                            </tr>
                            <tr>
                                <th>Tanggal Kunjungan</th>
                                <td>{{ \Carbon\Carbon::parse($ticket->visit_date)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Jam Kunjungan</th>
                                <td>{{ $ticket->visit_time ?? 'Fleksibel' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Tiket</th>
                                <td>
                                    @if($ticket->ticket_type == 'adult')
                                        <span class="badge bg-primary">👤 Dewasa</span>
                                    @elseif($ticket->ticket_type == 'child')
                                        <span class="badge bg-info">🧒 Anak</span>
                                    @else
                                        <span class="badge bg-success">👨‍👩‍👧‍👦 Keluarga</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah Tiket</th>
                                <td>{{ $ticket->number_of_tickets }} tiket</td>
                            </tr>
                            <tr>
                                <th>Harga per Tiket</th>
                                <td>Rp {{ number_format($ticket->price_per_ticket, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td><strong class="text-success">Rp {{ number_format($ticket->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <th>Status Tiket</th>
                                <td>{!! $ticket->status_label !!}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>{!! $ticket->payment_status_label !!}</td>
                            </tr>
                            @if($ticket->used_at)
                            <tr>
                                <th>Digunakan Pada</th>
                                <td>{{ \Carbon\Carbon::parse($ticket->used_at)->format('d F Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ========================================== -->
        <!-- BAGIAN BUKTI PEMBAYARAN (YANG PERLU DITAMBAHKAN) -->
        <!-- ========================================== -->
        @php
            $hasPaymentProof = !is_null($ticket->payment_proof) && $ticket->payment_proof !== '';
            $paymentProofUrl = null;
            if ($hasPaymentProof) {
                if (str_starts_with($ticket->payment_proof, 'http')) {
                    $paymentProofUrl = $ticket->payment_proof;
                } else {
                    $paymentProofUrl = asset('storage/' . $ticket->payment_proof);
                }
            }
        @endphp
        
        @if($hasPaymentProof)
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #c1a067;">
            <div class="card-header bg-transparent">
                <h6 class="mb-0">
                    <i class="fas fa-receipt me-2" style="color: #c1a067;"></i>
                    Bukti Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($ticket->bank_from)
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">🏦 Bank Asal Transfer</small>
                        <strong>{{ $ticket->bank_from }}</strong>
                    </div>
                    @endif
                    @if($ticket->account_name)
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">👤 Nama Pemilik Rekening</small>
                        <strong>{{ $ticket->account_name }}</strong>
                    </div>
                    @endif
                </div>
                
                <!-- Preview Gambar Bukti -->
                <div class="text-center mt-2">
                    <a href="{{ $paymentProofUrl }}" target="_blank">
                        <img src="{{ $paymentProofUrl }}" 
                             class="img-fluid rounded shadow-sm border" 
                             style="max-height: 300px; cursor: pointer;"
                             alt="Bukti Pembayaran">
                    </a>
                    <div class="mt-2">
                        <a href="{{ $paymentProofUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-external-link-alt"></i> Lihat Gambar Utuh
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-secondary mb-4">
            <i class="fas fa-clock me-2"></i>
            Customer belum mengupload bukti pembayaran.
        </div>
        @endif
        
        <!-- ========================================== -->
        <!-- BAGIAN VERIFIKASI PEMBAYARAN (HANYA UNTUK YANG MENUNGGU) -->
        <!-- ========================================== -->
        @if($ticket->payment_status == 'waiting_payment' && $hasPaymentProof)
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-check-double me-2"></i>
                    Verifikasi Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tickets.verify-payment', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tindakan</label>
                            <select name="action" class="form-select" required>
                                <option value="">-- Pilih Tindakan --</option>
                                <option value="verify">✅ Verifikasi (Tiket Aktif)</option>
                                <option value="reject">❌ Tolak (Minta Upload Ulang)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Catatan (jika menolak)</label>
                            <textarea name="notes" class="form-control" rows="1" placeholder="Contoh: Bukti transfer kurang jelas..."></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Verifikasi
                    </button>
                </form>
            </div>
        </div>
        @elseif($ticket->payment_status == 'paid')
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Pembayaran telah diverifikasi.</strong> Tiket sudah aktif dan dapat digunakan.
        </div>
        @elseif($ticket->rejection_reason)
        <div class="alert alert-danger mb-4">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Pembayaran ditolak.</strong> Alasan: {{ $ticket->rejection_reason }}
        </div>
        @endif
        
        <!-- ========================================== -->
        <!-- BAGIAN TANDAI SUDAH DIGUNAKAN (HANYA UNTUK TIKET AKTIF) -->
        <!-- ========================================== -->
        @if($ticket->status == 'active' && $ticket->payment_status == 'paid')
        <div class="text-end mt-3">
            <form action="{{ route('admin.tickets.mark-used', $ticket) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin menandai tiket ini sebagai sudah digunakan?')">
                    <i class="fas fa-check-circle me-1"></i> Tandai Sudah Digunakan
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection