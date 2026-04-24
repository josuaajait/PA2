

<?php $__env->startSection('title', 'Contact Us'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
        <p class="lead text-muted">We'd love to hear from you</p>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4">Get in Touch</h4>
                <div class="d-flex mb-3">
                    <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Address</h6>
                        <p class="text-muted">Jl. Raya Caldera No. 123, Kota Bandung, Jawa Barat</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <i class="fas fa-phone fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Phone</h6>
                        <p class="text-muted">(022) 1234567 | 0812-3456-7890</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Email</h6>
                        <p class="text-muted">info@caldera.com</p>
                    </div>
                </div>
                <div class="d-flex">
                    <i class="fas fa-clock fa-2x text-primary me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Opening Hours</h6>
                        <p class="text-muted">Resto: 10:00 - 22:00<br>Pool: 08:00 - 18:00</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4">Send Us a Message</h4>
                <form method="POST" action="<?php echo e(route('branding.contact.send')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="card border-0 shadow-sm overflow-hidden">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56415693663!2d107.5731568!3d-6.903489!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                width="100%" 
                height="300" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/contact.blade.php ENDPATH**/ ?>