<?php

namespace App\Http\Controllers\Branding;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request)
    {
        $query = Testimonial::approved();
        
        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }
        
        // Filter by service type
        if ($request->filled('service')) {
            $query->where('service_type', $request->service);
        }
        
        $testimonials = $query->latest()->paginate(12);
        
        // Rating statistics
        $ratingStats = [
            'average' => Testimonial::approved()->avg('rating') ?? 0,
            'total' => Testimonial::approved()->count(),
            '5_star' => Testimonial::approved()->where('rating', 5)->count(),
            '4_star' => Testimonial::approved()->where('rating', 4)->count(),
            '3_star' => Testimonial::approved()->where('rating', 3)->count(),
        ];
        
        // Service types for filter
        $serviceTypes = ['restaurant', 'pool', 'event'];
        
        return view('pages.testimonials', compact('testimonials', 'ratingStats', 'serviceTypes'));
    }
    
    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('pages.testimonials-create');
    }
    
    /**
     * Store a newly created testimonial in storage.
     */
public function store(Request $request)
    {
        // CEK RATE LIMITING MANUAL (double protection)
        $ip = $request->ip();
        $today = now()->toDateString();
        $cacheKey = "testimonial_limit_{$ip}_{$today}";
        
        $submissionCount = Cache::get($cacheKey, 0);
        
        if ($submissionCount >= 3) {
            return redirect()->back()
                ->with('error', 'Anda sudah mencapai batas maksimal 3 testimoni per hari. Silakan coba lagi besok.');
        }
        
        // Validasi dengan CAPTCHA (opsional)
        // if ($request->has('g-recaptcha-response')) {
        //     $this->validateCaptcha($request);
        // }
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'service_type' => 'nullable|string|in:restaurant,pool,event',
            'visit_date' => 'nullable|date|before_or_equal:today'
        ]);
        
        // Handle photo upload
        if ($request->hasFile('customer_photo')) {
            $path = $request->file('customer_photo')->store('testimonials', 'public');
            $validated['customer_photo'] = $path;
        }
        
        // Simpan data
        $validated['is_approved'] = false;
        $validated['is_featured'] = false;
        $validated['ip_address'] = $ip; // Tambahkan kolom ini di migration
        $validated['user_agent'] = $request->userAgent();
        
        Testimonial::create($validated);
        
        // Increment cache counter
        Cache::put($cacheKey, $submissionCount + 1, now()->endOfDay());
        
        return redirect()->route('branding.testimonials')
            ->with('success', 'Terima kasih atas testimoni Anda! Testimoni akan ditampilkan setelah diverifikasi oleh admin.');
    }
    
    // Optional: Validasi Google reCAPTCHA
    private function validateCaptcha(Request $request)
    {
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$request->input('g-recaptcha-response')}");
        $response = json_decode($response);
        
        if (!$response->success) {
            throw new \Exception('Verifikasi CAPTCHA gagal. Silakan coba lagi.');
        }
    }
    
    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        // Only show approved testimonials
        if (!$testimonial->is_approved) {
            abort(404);
        }
        
        return view('pages.testimonials-show', compact('testimonial'));
    }

    
}