<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'type', 
        'file_path', 
        'menu_id',
        'event_id',
        'promo_id',
        'testimonial_id',
        'category', 
        'description', 
        'is_featured', 
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relasi ke Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relasi ke Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi ke Promo
     */
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    /**
     * Relasi ke Testimonial
     */
    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }

    /**
     * Get parent model (untuk memudahkan)
     */
    public function parent()
    {
        if ($this->menu_id) {
            return $this->menu();
        } elseif ($this->event_id) {
            return $this->event();
        } elseif ($this->promo_id) {
            return $this->promo();
        } elseif ($this->testimonial_id) {
            return $this->testimonial();
        }
        return null;
    }

    // Scopes
    public function scopeForMenu($query)
    {
        return $query->whereNotNull('menu_id');
    }

    public function scopeForEvent($query)
    {
        return $query->whereNotNull('event_id');
    }

    public function scopeForPromo($query)
    {
        return $query->whereNotNull('promo_id');
    }

    public function scopeForTestimonial($query)
    {
        return $query->whereNotNull('testimonial_id');
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}