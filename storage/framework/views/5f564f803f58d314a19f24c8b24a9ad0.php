<footer class="footer pt-5 mt-5" style="background: #1a1a2e; color: #fff;">
    <div class="container">
        <div class="row">
            <!-- Brand Section -->
            <div class="col-md-4 mb-4">
                <div class="mb-3">
                    <i class="fas fa-fire fa-2x text-primary mb-2"></i>
                    <h5 class="fw-bold text-white mb-2">Caldera Resto & Pool</h5>
                    <p class="text-secondary small">Experience the perfect blend of culinary delight and refreshing pool experience.</p>
                </div>
                <div>
                    <ul class="d-flex list-unstyled gap-3">
                        <li><a href="#" class="text-secondary"><i class="fab fa-facebook-f fa-lg"></i></a></li>
                        <li><a href="#" class="text-secondary"><i class="fab fa-instagram fa-lg"></i></a></li>
                        <li><a href="#" class="text-secondary"><i class="fab fa-twitter fa-lg"></i></a></li>
                        <li><a href="#" class="text-secondary"><i class="fab fa-youtube fa-lg"></i></a></li>
                        <li><a href="#" class="text-secondary"><i class="fab fa-tiktok fa-lg"></i></a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-md-2 col-sm-6 mb-4">
                <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('branding.home')); ?>" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.about')); ?>" class="text-secondary text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.menu')); ?>" class="text-secondary text-decoration-none">Menu</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.pool')); ?>" class="text-secondary text-decoration-none">Pool</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.gallery')); ?>" class="text-secondary text-decoration-none">Gallery</a></li>
                </ul>
            </div>
            
            <!-- Information -->
            <div class="col-md-3 col-sm-6 mb-4">
                <h6 class="text-white fw-bold mb-3">Information</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('branding.promos')); ?>" class="text-secondary text-decoration-none">Promos & Events</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.testimonials')); ?>" class="text-secondary text-decoration-none">Testimonials</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('branding.contact')); ?>" class="text-secondary text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('reservation.table')); ?>" class="text-secondary text-decoration-none">Book a Table</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('reservation.ticket')); ?>" class="text-secondary text-decoration-none">Buy Pool Ticket</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="col-md-3 mb-4">
                <h6 class="text-white fw-bold mb-3">Contact Info</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                        <span class="text-secondary">Jl. Raya Caldera No. 123, Kota Bandung, Jawa Barat</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-phone text-primary mt-1 me-3"></i>
                        <span class="text-secondary">(022) 1234567</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-mobile-alt text-primary mt-1 me-3"></i>
                        <span class="text-secondary">0812-3456-7890</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                        <span class="text-secondary">info@caldera.com</span>
                    </li>
                </ul>
            </div>
            
            <!-- Opening Hours -->
            <div class="col-md-12">
                <hr class="bg-secondary opacity-25">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="text-secondary small mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Resto Hours:</strong> Monday - Friday: 10:00 - 22:00 | Saturday - Sunday: 09:00 - 23:00
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="text-secondary small mb-2">
                            <i class="fas fa-swimmer text-primary me-2"></i>
                            <strong>Pool Hours:</strong> Daily: 08:00 - 18:00
                        </p>
                    </div>
                </div>
                <hr class="bg-secondary opacity-25">
            </div>
            
            <!-- Copyright -->
            <div class="col-12 text-center mt-3">
                <p class="text-secondary small mb-0">
                    &copy; <?php echo e(date('Y')); ?> Caldera Resto & Pool. All rights reserved. 
                    <span class="mx-2">|</span> 
                    Designed with <i class="fas fa-heart text-danger"></i> for great experience
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background: #1a1a2e;
    color: #fff;
}

.footer a.text-secondary {
    transition: all 0.3s ease;
}

.footer a.text-secondary:hover {
    color: #667eea !important;
    transform: translateX(3px);
    display: inline-block;
}

.footer .list-unstyled li {
    transition: all 0.3s ease;
}

.footer .list-unstyled li:hover {
    transform: translateX(3px);
}

.footer hr {
    opacity: 0.2;
}

/* Dark mode support */
body.dark-mode .footer {
    background: #0d0d1a !important;
}

body.dark-mode .footer .text-secondary {
    color: #aaa !important;
}

body.dark-mode .footer hr {
    background-color: #fff;
}

/* Responsive */
@media (max-width: 768px) {
    .footer {
        text-align: center;
    }
    
    .footer .d-flex {
        justify-content: center;
    }
    
    .footer .col-md-3 .d-flex {
        justify-content: center;
    }
    
    .footer .text-md-start,
    .footer .text-md-end {
        text-align: center !important;
    }
}
</style><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/components/footer.blade.php ENDPATH**/ ?>