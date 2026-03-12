<nav class="navbar navbar-expand-lg w-100" id="mainNav" 
     style="position: absolute; top: 0; left: 0; right: 0; z-index: 1030; background: transparent; padding: 1rem 0; transition: all 0.3s ease;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}" style="font-size: 1.5rem; transition: color 0.3s ease;">
            <i class="fas fa-rocket me-2"></i>
            Material Kit 2 Pro
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="#" style="font-weight: 500; transition: color 0.3s ease;">Pages</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="#" style="font-weight: 500; transition: color 0.3s ease;">Sections</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="#" style="font-weight: 500; transition: color 0.3s ease;">Docs</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a href="#" class="btn btn-primary" style="background: #667eea; border: none; padding: 0.5rem 1.5rem; transition: all 0.3s ease;" 
                       onclick="event.preventDefault(); window.showNotification();">
                        <i class="fas fa-shopping-cart me-2"></i>Buy Now
                    </a>
                </li>
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
            const scrollThreshold = heroHeight * 0.3; // 30% dari tinggi hero
            
            if (scrolled > scrollThreshold) {
                // Jika scroll melebihi threshold, ganti ke fixed
                nav.classList.remove('navbar-transparent');
                nav.classList.add('navbar-fixed');
                
                // Pastikan style inline tidak mengganggu
                nav.style.position = '';
                nav.style.background = '';
                nav.style.boxShadow = '';
            } else {
                // Jika masih di atas threshold, tetap transparan
                nav.classList.remove('navbar-fixed');
                nav.classList.add('navbar-transparent');
                
                // Reset style inline
                nav.style.position = '';
                nav.style.background = '';
                nav.style.boxShadow = '';
            }
        }
        
        // Initial call
        updateNavbar();
        
        // Add scroll event listener dengan throttle
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
        
        // Update saat window diresize
        window.addEventListener('resize', function() {
            heroHeight = document.querySelector('.page-header').offsetHeight;
            updateNavbar();
        });
    });
</script>