

<?php $__env->startSection('title', 'Pembelian Tiket Berhasil'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Icon Success -->
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold mb-3">Pembelian Tiket Berhasil!</h2>
                    <p class="text-muted mb-4">Terima kasih telah membeli tiket kolam renang Caldera.</p>
                    
                    <!-- Ticket Code -->
                    <div class="bg-light rounded-3 p-4 mb-4">
                        <small class="text-muted">Kode Tiket Anda</small>
                        <h3 class="fw-bold text-primary mb-0"><?php echo e($ticket->ticket_code); ?></h3>
                    </div>
                    
                    <!-- Ticket Details -->
                    <div class="text-start mb-4">
                        <h5 class="fw-bold mb-3">Detail Tiket:</h5>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <span class="text-muted">Nama:</span>
                                <p class="fw-bold mb-0"><?php echo e($ticket->customer_name); ?></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted">Tanggal Kunjungan:</span>
                                <p class="fw-bold mb-0"><?php echo e(\Carbon\Carbon::parse($ticket->visit_date)->format('d F Y')); ?></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted">Jenis Tiket:</span>
                                <p class="fw-bold mb-0"><?php echo e(ucfirst($ticket->ticket_type)); ?></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted">Jumlah:</span>
                                <p class="fw-bold mb-0"><?php echo e($ticket->number_of_tickets); ?> tiket</p>
                            </div>
                            <div class="col-md-12">
                                <span class="text-muted">Total Pembayaran:</span>
                                <p class="fw-bold text-primary mb-0">Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Info -->
                    <div class="alert alert-warning text-start">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi Pembayaran:</strong>
                        <p class="mb-0 mt-1 small">Silakan lakukan pembayaran ke rekening berikut:</p>
                        <hr class="my-2">
                        <p class="mb-0 small">
                            Bank BCA: 1234567890 a.n. Caldera Resto<br>
                            Bank Mandiri: 0987654321 a.n. Caldera Resto
                        </p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?php echo e(route('reservation.ticket.view', $ticket->ticket_code)); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i> Lihat Detail
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-upload me-2"></i> Upload Bukti Bayar
                        </button>
                        <a href="<?php echo e(route('branding.home')); ?>" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('reservation.payment.upload')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="booking_code" value="<?php echo e($ticket->ticket_code); ?>">
                <input type="hidden" name="amount" value="<?php echo e($ticket->total_amount); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="transfer">Transfer Bank</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Bukti</label>
                        <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, PDF. Max 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/reservation/ticket/success.blade.php ENDPATH**/ ?>