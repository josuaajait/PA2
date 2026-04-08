<?php

namespace App\Http\Controllers\Branding;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
        
        // Set default values
        $validated['is_approved'] = false; // Need admin approval
        $validated['is_featured'] = false;
        
        Testimonial::create($validated);
        
        return redirect()->route('branding.testimonials')
            ->with('success', 'Terima kasih atas testimoni Anda! Testimoni akan ditampilkan setelah diverifikasi oleh admin.');
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