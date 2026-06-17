<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Tiket Berhasil - Caldera</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f8f6f2; font-family: 'Roboto', sans-serif; }

        .ticket-navbar {
            background: #1c3451;
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .caldera-brand {
            font-family: 'Playfair Display', serif;
            color: #c1a067;
            font-size: 1.3rem;
            font-weight: 700;
            text-decoration: none;
            line-height: 1.2;
        }

        .btn-back-nav {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-back-nav:hover {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .caldera-card {
            border-radius: 20px;
            border-top: 3px solid #c1a067;
            border: none;
        }

        .section-divider {
            width: 50px;
            height: 3px;
            background: #c1a067;
            margin: 12px auto 16px;
            border-radius: 2px;
        }

        .ticket-code-box {
            background: linear-gradient(135deg, #f8f6f2, #f0ebe0);
            padding: 15px 25px;
            border-radius: 50px;
            display: inline-block;
            border: 1px solid #e8e0d0;
        }

        .info-card {
            transition: all 0.2s;
            border: 1px solid #e8e0d0;
        }

        .info-card:hover {
            border-color: #c1a067;
            transform: translateY(-2px);
        }

        .alert-custom {
            background: #fdfcfa;
            border-left: 4px solid #c1a067;
            padding: 16px 20px;
            border-radius: 12px;
        }

        .bank-detail {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e8e0d0;
        }

        .btn-upload {
            background: linear-gradient(135deg, #c1a067, #a8894f);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-upload:hover {
            background: linear-gradient(135deg, #a8894f, #8b6d3f);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-custom {
            background: white;
            color: #1c3451;
            border: 1.5px solid #1c3451;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-outline-custom:hover {
            background: #1c3451;
            color: white;
            transform: translateY(-2px);
        }

        .btn-modal-close {
            background: #f1f5f9;
            color: #1c3451;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-upload-modal {
            background: linear-gradient(135deg, #1c3451, #01516e);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .caldera-input {
            border: 1.5px solid #e8e0d0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
        }

        .caldera-input:focus {
            border-color: #c1a067;
            box-shadow: 0 0 0 3px rgba(193,160,103,0.1);
            outline: none;
        }

        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .success-icon { animation: scaleIn 0.5s ease; }

        @media (max-width: 576px) {
            .btn-upload, .btn-outline-custom { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navbar Minimal -->
    <nav class="ticket-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?php echo e(route('branding.home')); ?>" class="caldera-brand">
                Caldera Resto<br>& Pool
            </a>
            <a href="<?php echo e(route('customer.tickets')); ?>" class="btn-back-nav">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Tiket Saya
            </a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm caldera-card">
                    <div class="card-body p-5">

                        <!-- Success Icon -->
                        <div class="text-center mb-4">
                            <div class="success-icon mb-3">
                                <i class="fas fa-check-circle fa-5x" style="color: #25D366;"></i>
                            </div>
                            <h2 class="fw-bold mb-2" style="color: #1c3451; font-family: 'Playfair Display', serif;">Pembelian Tiket Berhasil!</h2>
                            <div class="section-divider mx-auto"></div>
                            <p class="text-muted">Terima kasih telah membeli tiket kolam renang Caldera.</p>
                        </div>

                        <!-- Ticket Code -->
                        <div class="text-center mb-4">
                            <div class="ticket-code-box">
                                <small class="text-muted text-uppercase fw-semibold d-block mb-1">Kode Tiket Anda</small>
                                <h3 class="fw-bold mb-0" style="color: #1c3451; letter-spacing: 2px;">
                                    <i class="fas fa-qrcode me-2" style="color: #c1a067;"></i>
                                    <?php echo e($ticket->ticket_code); ?>

                                </h3>
                            </div>
                        </div>

                        <!-- Ticket Details -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #1c3451;">
                                <i class="fas fa-ticket-alt me-2" style="color: #c1a067;"></i> Detail Tiket
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                        <small class="text-muted d-block mb-1">Nama Customer</small>
                                        <p class="fw-bold mb-0" style="color: #1c3451;">
                                            <i class="fas fa-user me-1" style="color: #c1a067;"></i>
                                            <?php echo e($ticket->customer_name); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                        <small class="text-muted d-block mb-1">Tanggal Kunjungan</small>
                                        <p class="fw-bold mb-0" style="color: #1c3451;">
                                            <i class="fas fa-calendar-day me-1" style="color: #c1a067;"></i>
                                            <?php echo e(\Carbon\Carbon::parse($ticket->visit_date)->format('d F Y')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                        <small class="text-muted d-block mb-1">Jenis Tiket</small>
                                        <p class="fw-bold mb-0" style="color: #1c3451;">
                                            <i class="fas fa-tag me-1" style="color: #c1a067;"></i>
                                            <?php
                                                $ticketTypeLabels = [
                                                    'adult' => 'Dewasa',
                                                    'child' => 'Anak-anak',
                                                    'family' => 'Keluarga'
                                                ];
                                            ?>
                                            <?php echo e($ticketTypeLabels[$ticket->ticket_type] ?? ucfirst($ticket->ticket_type)); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                        <small class="text-muted d-block mb-1">Jumlah Tiket</small>
                                        <p class="fw-bold mb-0" style="color: #1c3451;">
                                            <i class="fas fa-shopping-cart me-1" style="color: #c1a067;"></i>
                                            <?php echo e($ticket->number_of_tickets); ?> tiket
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="info-card p-3 rounded-3" style="background: #f8f6f2;">
                                        <small class="text-muted d-block mb-1">Total Pembayaran</small>
                                        <p class="fw-bold mb-0" style="color: #c1a067; font-size: 1.2rem;">
                                            <i class="fas fa-money-bill me-1"></i>
                                            Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pembayaran -->
                        <?php if($ticket->payment_status == 'waiting_payment'): ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Status: Menunggu Verifikasi</strong><br>
                            Bukti pembayaran Anda sudah diupload dan sedang menunggu verifikasi admin.
                        </div>
                        <?php elseif($ticket->payment_status == 'paid'): ?>
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Status: Lunas</strong><br>
                            Pembayaran Anda sudah diverifikasi. Tiket sudah aktif.
                        </div>
                        <?php endif; ?>

                        <!-- Payment Information -->
                        <?php if(!$ticket->payment_proof && $ticket->payment_status != 'paid'): ?>
                        <div class="alert-custom mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card fa-lg me-3" style="color: #c1a067;"></i>
                                <div>
                                    <strong class="small" style="color: #1c3451;">Informasi Pembayaran:</strong>
                                    <p class="mb-1 small text-muted">Silakan lakukan pembayaran ke rekening berikut:</p>
                                    <div class="mt-2">
                                        <div class="bank-detail mb-2">
                                            <span class="fw-semibold" style="color: #1c3451;">BCA</span>
                                            <span class="text-muted mx-2">|</span>
                                            <span>1234567890</span>
                                            <span class="text-muted mx-2">|</span>
                                            <small>a.n. Caldera Resto</small>
                                        </div>
                                        <div class="bank-detail">
                                            <span class="fw-semibold" style="color: #1c3451;">Mandiri</span>
                                            <span class="text-muted mx-2">|</span>
                                            <span>0987654321</span>
                                            <span class="text-muted mx-2">|</span>
                                            <small>a.n. Caldera Resto</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Bukti yang Sudah Diupload -->
                        <?php if($ticket->payment_proof): ?>
                        <?php
                            $paymentProofUrl = str_starts_with($ticket->payment_proof, 'http')
                                ? $ticket->payment_proof
                                : asset('storage/' . $ticket->payment_proof);
                        ?>
                        <div class="card mb-4" style="border-left: 4px solid #c1a067; background: #f8f6f2;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-receipt me-2" style="color: #c1a067;"></i>
                                    Bukti Pembayaran yang Telah Diupload
                                </h6>
                                <div class="row">
                                    <?php if($ticket->bank_from): ?>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">Bank Asal Transfer</small>
                                        <strong><?php echo e($ticket->bank_from); ?></strong>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($ticket->account_name): ?>
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block">Nama Pemilik Rekening</small>
                                        <strong><?php echo e($ticket->account_name); ?></strong>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="<?php echo e($paymentProofUrl); ?>" target="_blank">
                                        <img src="<?php echo e($paymentProofUrl); ?>"
                                             class="img-fluid rounded shadow-sm border"
                                             style="max-height: 200px; cursor: pointer;"
                                             alt="Bukti Pembayaran">
                                    </a>
                                    <div class="mt-2">
                                        <a href="<?php echo e($paymentProofUrl); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-external-link-alt"></i> Lihat Bukti Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Important Note -->
                        <?php if($ticket->payment_status != 'paid'): ?>
                        <div class="alert alert-warning small mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Tiket hanya akan aktif setelah pembayaran diverifikasi oleh admin.
                        </div>
                        <?php else: ?>
                        <div class="alert alert-success small mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Tiket Aktif!</strong> Silakan tunjukkan kode tiket ini saat datang ke Caldera.
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
                            <?php if(!$ticket->payment_proof && $ticket->payment_status != 'paid'): ?>
                            <button type="button" class="btn btn-upload" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                <i class="fas fa-upload me-2"></i> Upload Bukti Bayar
                            </button>
                            <?php elseif($ticket->payment_status == 'waiting_payment'): ?>
                            <div class="alert alert-info mb-0 px-4 py-2">
                                <i class="fas fa-spinner fa-spin me-2"></i> Menunggu Verifikasi Admin
                            </div>
                            <?php elseif($ticket->payment_status == 'paid'): ?>
                            <div class="alert alert-success mb-0 px-4 py-2">
                                <i class="fas fa-check-circle me-2"></i> Pembayaran Lunas
                            </div>
                            <?php endif; ?>

                            <a href="<?php echo e(route('customer.tickets')); ?>" class="btn btn-outline-custom">
                                <i class="fas fa-ticket-alt me-2"></i> Lihat Semua Tiket Saya
                            </a>

                            <a href="<?php echo e(route('branding.home')); ?>" class="btn btn-outline-custom">
                                <i class="fas fa-home me-2"></i> Kembali ke Beranda
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Pembayaran -->
    <?php if(!$ticket->payment_proof && $ticket->payment_status != 'paid'): ?>
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #1c3451;">
                        <i class="fas fa-upload me-2" style="color: #c1a067;"></i>
                        Upload Bukti Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('reservation.ticket.payment.upload')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ticket_code" value="<?php echo e($ticket->ticket_code); ?>">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1c3451;">Kode Tiket</label>
                            <input type="text" class="form-control caldera-input" value="<?php echo e($ticket->ticket_code); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1c3451;">Total Pembayaran</label>
                            <input type="text" class="form-control caldera-input" value="Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1c3451;">Bank Asal Transfer</label>
                            <select name="bank_from" class="form-control caldera-input" required>
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="MANDIRI">MANDIRI</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                                <option value="CIMB">CIMB Niaga</option>
                                <option value="DANAMON">Danamon</option>
                                <option value="PERMATA">Permata</option>
                                <option value="SEABANK">SeaBank</option>
                                <option value="JAGO">Bank Jago</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1c3451;">Nama Pemilik Rekening</label>
                            <input type="text" name="account_name" class="form-control caldera-input" placeholder="Contoh: JOHN DOE" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1c3451;">Upload Bukti Transfer</label>
                            <input type="file" name="payment_proof" class="form-control caldera-input" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        </div>
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-2"></i>
                            Setelah upload bukti, admin akan memverifikasi pembayaran Anda.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-upload-modal">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/reservation/ticket/view.blade.php ENDPATH**/ ?>