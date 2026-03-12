<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'slug', 
        'description', 
        'start_date', 
        'end_date',
        'location', 
        'max_participants', 
        'ticket_price', 
        'event_schedule',
        'contact_info', 
        'is_featured', 
        'is_active', 
        'promo_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ticket_price' => 'decimal:2',
        'event_schedule' => 'array',
        'contact_info' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get all galleries for this event
     */
    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    /**
     * Get featured gallery for this event
     */
    public function featuredGallery()
    {
        return $this->morphOne(Gallery::class, 'galleryable')
                    ->where('is_featured', true);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
                     ->where('is_active', true)
                     ->orderBy('start_date');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}