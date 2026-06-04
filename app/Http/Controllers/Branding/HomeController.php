<?php

namespace App\Http\Controllers\Branding;

use App\Http\Controllers\Controller;
use App\Services\PromoServiceClient;
use App\Models\Menu;
use App\Models\Testimonial;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $promoService;

    public function __construct(PromoServiceClient $promoService)
    {
        $this->promoService = $promoService;
    }

    public function index()
    {
        // 🔥 AMBIL PROMO (PRIORITAS DARI MICROSERVICE)
        try {
            $response = $this->promoService->getAll();
            
            if (isset($response['data'])) {
                $promos = collect($response['data']);
            } else {
                $promos = collect($response);
            }
            
            $now = Carbon::now('Asia/Jakarta');
            $activePromos = $promos->filter(function ($promo) use ($now) {
                $promo = (object) $promo;
                $startDate = Carbon::parse($promo->start_date);
                $endDate = Carbon::parse($promo->end_date);
                
                return $promo->is_active 
                    && $now->between($startDate, $endDate)
                    && $promo->promo_type !== 'event';
            })->take(3);
            
            // Jika microservice mengembalikan data kosong, fallback ke database
            if ($activePromos->isEmpty()) {
                throw new \Exception('No data from microservice');
            }
            
        } catch (\Exception $e) {
            Log::warning('Falling back to local database for promos', [
                'error' => $e->getMessage()
            ]);
            
            // 🔥 FALLBACK KE DATABASE MONOLITH
            $activePromos = \App\Models\Promo::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('promo_type', '!=', 'event')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
        
        // 🔥 AMBIL EVENT (PRIORITAS DARI MICROSERVICE)
        try {
            $response = $this->promoService->getAll();
            
            if (isset($response['data'])) {
                $promos = collect($response['data']);
            } else {
                $promos = collect($response);
            }
            
            $now = Carbon::now('Asia/Jakarta');
            $upcomingEvents = $promos->filter(function ($promo) use ($now) {
                $promo = (object) $promo;
                $endDate = Carbon::parse($promo->end_date);
                
                return $promo->promo_type === 'event' 
                    && $promo->is_active 
                    && $endDate->isAfter($now);
            })->take(3);
            
            if ($upcomingEvents->isEmpty()) {
                throw new \Exception('No events from microservice');
            }
            
        } catch (\Exception $e) {
            Log::warning('Falling back to local database for events', [
                'error' => $e->getMessage()
            ]);
            
            // 🔥 FALLBACK KE DATABASE MONOLITH (gunakan model Event jika ada)
            $upcomingEvents = collect([]); // atau ambil dari model Event jika ada
        }
        
        // 🔥 AMBIL MENU (dari database monolith)
        $recommendedMenus = Cache::remember('homepage_recommended_menus', 3600, function () {
            return Menu::where('is_available', true)
                ->where('is_recommended', true)
                ->latest()
                ->take(6)
                ->get();
        });
        
        // 🔥 AMBIL TESTIMONIAL (dari database monolith)
        $testimonials = Cache::remember('homepage_testimonials', 3600, function () {
            $testimonials = Testimonial::where('is_approved', true)
                ->where('is_featured', true)
                ->latest()
                ->take(3)
                ->get();
            
            if ($testimonials->count() < 3) {
                $moreTestimonials = Testimonial::where('is_approved', true)
                    ->whereNotIn('id', $testimonials->pluck('id'))
                    ->latest()
                    ->take(3 - $testimonials->count())
                    ->get();
                $testimonials = $testimonials->merge($moreTestimonials);
            }
            
            return $testimonials;
        });
        
        // 🔥 GALLERY (dari database monolith)
        $featuredGallery = Cache::remember('homepage_featured_gallery', 3600, function () {
            return Gallery::where('is_featured', true)
                ->where('type', 'image')
                ->take(8)
                ->get();
        });
        
        // Stats untuk home
        $stats = [
            [
                'id' => 'counter1', 
                'value' => Testimonial::approved()->count() * 100 ?: 1500, 
                'label' => 'Happy Clients', 
                'icon' => 'fa-smile', 
                'color' => 'primary'
            ],
            [
                'id' => 'counter2', 
                'value' => Menu::available()->count() ?: 2500, 
                'label' => 'Menu Variants', 
                'icon' => 'fa-utensils', 
                'color' => 'success'
            ],
            [
                'id' => 'counter3', 
                'value' => $upcomingEvents->count() ?: 50, 
                'label' => 'Events', 
                'icon' => 'fa-calendar', 
                'color' => 'info'
            ],
            [
                'id' => 'counter4', 
                'value' => Testimonial::approved()->count() ?: 25, 
                'label' => 'Testimonials', 
                'icon' => 'fa-star', 
                'color' => 'warning'
            ],
        ];

        $features = [
            [
                'icon' => 'fa-paint-brush',
                'gradient' => 'primary',
                'title' => 'Modern Design',
                'description' => 'Beautiful and intuitive interface with smooth animations and interactions.',
                'feature' => 'design'
            ],
            [
                'icon' => 'fa-code',
                'gradient' => 'success',
                'title' => 'Clean Code',
                'description' => 'Well-organized and documented code for easy customization and maintenance.',
                'feature' => 'code'
            ],
            [
                'icon' => 'fa-headset',
                'gradient' => 'info',
                'title' => '24/7 Support',
                'description' => 'Dedicated support team ready to help you with any questions or issues.',
                'feature' => 'support'
            ],
        ];

        $testimonialsData = [
            [
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'quote' => 'The support team is amazing! They helped me customize everything I needed within hours.',
                'name' => 'Emily Rodriguez',
                'position' => 'Marketing Director',
                'rating' => 5
            ],
            [
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'quote' => 'Incredible attention to detail and the animations are buttery smooth. Highly recommended!',
                'name' => 'Michael Chen',
                'position' => 'Product Designer',
                'rating' => 5
            ],
            [
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'quote' => 'This is by far the best template I\'ve ever used. The design is stunning and the code is so clean!',
                'name' => 'Sarah Johnson',
                'position' => 'CEO, TechStart',
                'rating' => 5
            ],
        ];

        return view('pages.home', compact(
            'stats', 
            'features', 
            'testimonialsData', 
            'activePromos', 
            'recommendedMenus', 
            'upcomingEvents', 
            'featuredGallery'
        ));
    }
}