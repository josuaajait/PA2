

<?php $__env->startSection('title', 'Lupa Password - Caldera Resto & Pool'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-transparent border-0 text-center pt-4 pb-0">
                    <h5 class="mb-0" style="font-family: 'Playfair Display', serif; color: #1c3451; font-weight: 700;">Lupa Password</h5>
                    <div class="section-divider" style="width: 50px; height: 3px; background: #c1a067; margin: 12px auto 0; border-radius: 2px;"></div>
                </div>
                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success" style="background: #e8f5e9; border-color: #c1a067; color: #2e7d32; border-radius: 12px;">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <p class="text-muted text-center mb-4" style="color: #6c757d !important;">
                        Masukkan email Anda, kami akan mengirimkan link reset password
                    </p>

                    <form method="POST" action="<?php echo e(route('password.otp.send')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="form-label" style="color: #1c3451; font-weight: 500;">Alamat Email</label>
                            <input type="email" name="email" class="form-control" required 
                                   style="border-radius: 12px; border: 1.5px solid #e2e8f0; padding: 12px 16px; transition: all 0.2s;"
                                   onfocus="this.style.borderColor='#c1a067'; this.style.boxShadow='0 0 0 3px rgba(193,160,103,0.1)'"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        </div>
                        <button type="submit" class="btn w-100" 
                                style="background: #1c3451; color: white; border-radius: 40px; padding: 12px; font-weight: 600; transition: all 0.3s; border: none;">
                            Kirim Link Reset
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('login')); ?>" style="color: #c1a067; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -10px rgba(28,52,81,0.15) !important;
    }
    .btn:hover {
        background: #c1a067 !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(193,160,103,0.3);
    }
    .form-control:focus {
        border-color: #c1a067;
        box-shadow: 0 0 0 3px rgba(193,160,103,0.1);
        outline: none;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>