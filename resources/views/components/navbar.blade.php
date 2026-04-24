<nav class="navbar navbar-expand-lg w-100" id="mainNav" 
     style="position: fixed; top: 0; left: 0; right: 0; z-index: 1030; padding: 1rem 0; transition: all 0.3s ease;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('branding.home') }}" style="font-size: 1.5rem; transition: color 0.3s ease;">
            <i class="fas fa-fire me-2"></i>
            Caldera Resto & Pool
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                style="background: rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.2);">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.home') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.about') }}" style="font-weight: 500; padding: 0.5rem 1rem;">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.menu') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.pool') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Pool</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.promos') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Promos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.gallery') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.testimonials') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Testimonials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branding.contact') }}" style="font-weight: 500; padding: 0.5rem 1rem;">Contact</a>
                </li>
                
                <li class="nav-item">
                    <span class="mx-2 text-muted">|</span>
                </li>
                
                <!-- Book Table Button - Hanya untuk customer yang login -->
                @auth
                    @if(Auth::user()->role == 'customer')
                        <li class="nav-item">
                            <a href="{{ route('reservation.table') }}" class="btn btn-primary ms-2" style="background: #667eea; border: none; padding: 0.5rem 1.2rem; border-radius: 8px;">
                                <i class="fas fa-calendar-alt me-2"></i>Book Table
                            </a>
                        </li>
                    @endif
                @endauth
                
                <!-- Authentication Links -->
                @auth
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                        <!-- Admin/Staff Dropdown -->
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" style="font-weight: 500; padding: 0.5rem 1rem;">
                                <i class="fas fa-user-shield me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Customer Dropdown -->
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" style="font-weight: 500; padding: 0.5rem 1rem;">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-user me-2"></i>My Profile
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.reservations') }}">
                                    <i class="fas fa-calendar-alt me-2"></i>My Reservations
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.tickets') }}">
                                    <i class="fas fa-ticket-alt me-2"></i>My Tickets
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                @else
                    <!-- Guest (Belum Login) -->
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary" style="border-radius: 8px; padding: 0.5rem 1.2rem;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-primary" style="background: #667eea; border: none; border-radius: 8px; padding: 0.5rem 1.2rem;">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Style dan script sama seperti sebelumnya -->
 <style>
/* Navbar default - transparan */
#mainNav {
    background: transparent;
    transition: all 0.3s ease;
}

#mainNav .navbar-brand,
#mainNav .nav-link {
    color: #333 !important;
}

#mainNav .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

/* Navbar saat di-scroll - putih */
#mainNav.navbar-scrolled {
    background: white !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

#mainNav.navbar-scrolled .navbar-brand,
#mainNav.navbar-scrolled .nav-link {
    color: #333 !important;
}

/* Navbar untuk halaman dengan hero (home) */
body.has-hero #mainNav {
    background: transparent;
}

body.has-hero #mainNav .navbar-brand,
body.has-hero #mainNav .nav-link {
    color: white !important;
}

body.has-hero #mainNav .btn-outline-primary {
    border-color: white;
    color: white;
}

body.has-hero #mainNav.navbar-scrolled {
    background: white !important;
}

body.has-hero #mainNav.navbar-scrolled .navbar-brand,
body.has-hero #mainNav.navbar-scrolled .nav-link {
    color: #333 !important;
}

/* Dark mode */
body.dark-mode #mainNav.navbar-scrolled {
    background: #1a1a1a !important;
    border-bottom: 1px solid #404040;
}

body.dark-mode #mainNav.navbar-scrolled .navbar-brand,
body.dark-mode #mainNav.navbar-scrolled .nav-link {
    color: #fff !important;
}

/* Dropdown */
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

body.dark-mode .dropdown-menu {
    background: #2d2d2d;
}

body.dark-mode .dropdown-item {
    color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
    .navbar-nav {
        padding: 1rem 0;
    }
    .navbar-nav .nav-item {
        margin: 0.25rem 0 !important;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.getElementById('mainNav');
        const hasHero = document.querySelector('.page-header') !== null;
        
        if (hasHero) {
            document.body.classList.add('has-hero');
        }
        
        function updateNavbar() {
            if (window.scrollY > 50) {
                nav.classList.add('navbar-scrolled');
            } else {
                nav.classList.remove('navbar-scrolled');
            }
        }
        
        updateNavbar();
        window.addEventListener('scroll', updateNavbar);
    });
</script>