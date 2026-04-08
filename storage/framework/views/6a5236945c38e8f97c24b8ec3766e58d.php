<nav class="navbar navbar-expand-lg w-100" id="mainNav" 
     style="position: absolute; top: 0; left: 0; right: 0; z-index: 1030; background: transparent; padding: 1rem 0; transition: all 0.3s ease;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="<?php echo e(route('branding.home')); ?>" style="font-size: 1.5rem; transition: color 0.3s ease;">
            <i class="fas fa-fire me-2"></i>
            Caldera Resto & Pool
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Menu Links -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.home')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.about')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.menu')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.pool')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Pool</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.promos')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Promos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.gallery')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.testimonials')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Testimonials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('branding.contact')); ?>" style="font-weight: 500; padding: 0.5rem 1rem; transition: color 0.3s ease;">Contact</a>
                </li>
                
                <!-- Separator -->
                <li class="nav-item">
                    <span class="text-white-50 mx-2">|</span>
                </li>
                
                <!-- Book Table Button -->
                <li class="nav-item">
                    <a href="<?php echo e(route('reservation.table')); ?>" class="btn btn-primary ms-2" style="background: #667eea; border: none; padding: 0.5rem 1.2rem; border-radius: 8px; transition: all 0.3s ease;">
                        <i class="fas fa-calendar-alt me-2"></i>Book Table
                    </a>
                </li>
                
                <!-- Authentication Links -->
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" style="font-weight: 500; padding: 0.5rem 1rem;">
                            <i class="fas fa-user-circle me-1"></i><?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>My Reservations</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-ticket-alt me-2"></i>My Tickets</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-2">
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light" style="border-radius: 8px; padding: 0.5rem 1.2rem; transition: all 0.3s ease;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary" style="background: #667eea; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; transition: all 0.3s ease;">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Style untuk navbar saat di-scroll */
.navbar-fixed {
    position: fixed !important;
    background: white !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    animation: slideDown 0.3s ease;
}

.navbar-fixed .nav-link,
.navbar-fixed .navbar-brand {
    color: #333 !important;
}

.navbar-fixed .btn-primary {
    background: #667eea !important;
    color: white !important;
}

.navbar-fixed .btn-outline-light {
    border-color: #667eea !important;
    color: #667eea !important;
}

.navbar-fixed .btn-outline-light:hover {
    background: #667eea !important;
    color: white !important;
}

.navbar-fixed .dropdown-toggle {
    color: #333 !important;
}

.navbar-fixed .text-white-50 {
    color: #6c757d !important;
}

/* Style untuk navbar transparan (default) */
.navbar-transparent {
    position: absolute !important;
    background: transparent !important;
    box-shadow: none !important;
}

.navbar-transparent .nav-link,
.navbar-transparent .navbar-brand {
    color: white !important;
}

.navbar-transparent .btn-primary {
    background: #667eea !important;
    color: white !important;
}

.navbar-transparent .btn-outline-light {
    border-color: white !important;
    color: white !important;
}

.navbar-transparent .btn-outline-light:hover {
    background: white !important;
    color: #667eea !important;
}

.navbar-transparent .dropdown-toggle {
    color: white !important;
}

.navbar-transparent .text-white-50 {
    color: rgba(255,255,255,0.5) !important;
}

/* Animasi */
@keyframes slideDown {
    from {
        transform: translateY(-100%);
    }
    to {
        transform: translateY(0);
    }
}

/* Dark mode styles */
body.dark-mode .navbar-fixed {
    background: #1a1a1a !important;
    border-bottom: 1px solid #404040;
}

body.dark-mode .navbar-fixed .nav-link,
body.dark-mode .navbar-fixed .navbar-brand {
    color: #fff !important;
}

body.dark-mode .navbar-fixed .dropdown-toggle {
    color: #fff !important;
}

body.dark-mode .navbar-fixed .text-white-50 {
    color: #6c757d !important;
}

/* Dropdown menu styling */
.dropdown-menu {
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: none;
    padding: 0.5rem;
    margin-top: 0.5rem;
}

.dropdown-item {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.dropdown-item i {
    width: 20px;
}

body.dark-mode .dropdown-menu {
    background: #2d2d2d;
}

body.dark-mode .dropdown-item {
    color: #fff;
}

body.dark-mode .dropdown-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Navbar item spacing - konsisten */
.navbar-nav .nav-item {
    margin: 0 2px;
}

/* Button hover effects */
.btn-primary:hover,
.btn-outline-light:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 991px) {
    .navbar-nav {
        padding: 1rem 0;
    }
    
    .navbar-nav .nav-item {
        margin: 0.25rem 0 !important;
    }
    
    .navbar-nav .nav-link {
        padding: 0.5rem 0 !important;
    }
    
    .btn-outline-light,
    .btn-primary {
        display: inline-block;
        width: auto;
        margin: 0.25rem 0 !important;
    }
    
    .navbar-nav .ms-2 {
        margin-left: 0 !important;
    }
    
    .text-white-50 {
        display: none;
    }
}

@media (min-width: 992px) {
    .navbar-nav {
        gap: 0.25rem;
    }
}
</style>

<script>
    // Navbar scroll effect yang lebih baik
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.getElementById('mainNav');
        const heroSection = document.querySelector('.page-header');
        const heroHeight = heroSection ? heroSection.offsetHeight : 400;
        
        // Fungsi untuk update navbar berdasarkan scroll
        function updateNavbar() {
            const scrolled = window.scrollY;
            const scrollThreshold = heroHeight * 0.3;
            
            if (scrolled > scrollThreshold) {
                nav.classList.remove('navbar-transparent');
                nav.classList.add('navbar-fixed');
                nav.style.position = '';
                nav.style.background = '';
                nav.style.boxShadow = '';
            } else {
                nav.classList.remove('navbar-fixed');
                nav.classList.add('navbar-transparent');
                nav.style.position = '';
                nav.style.background = '';
                nav.style.boxShadow = '';
            }
        }
        
        updateNavbar();
        
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateNavbar();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        window.addEventListener('resize', function() {
            const newHeroHeight = document.querySelector('.page-header');
            if (newHeroHeight) {
                heroHeight = newHeroHeight.offsetHeight;
            }
            updateNavbar();
        });
    });
    
    // Global notification function
    window.showNotification = function() {
        const notification = document.createElement('div');
        notification.className = 'alert alert-success position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Success!</strong> Welcome to Caldera Resto & Pool!
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    };
</script><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/components/navbar.blade.php ENDPATH**/ ?>