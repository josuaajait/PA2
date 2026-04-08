<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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
     * Get the parent galleryable model (polymorphic)
     */
    public function parent()
    {
        if ($this->menu_id) {
            return $this->belongsTo(Menu::class, 'menu_id');
        } elseif ($this->event_id) {
            return $this->belongsTo(Event::class, 'event_id');
        } elseif ($this->promo_id) {
            return $this->belongsTo(Promo::class, 'promo_id');
        } elseif ($this->testimonial_id) {
            return $this->belongsTo(Testimonial::class, 'testimonial_id');
        }
        return null;
    }

    /**
     * Get image URL
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    /**
     * Scope for images only
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope for videos only
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Scope for featured items
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Delete file when gallery is deleted
        static::deleting(function ($gallery) {
            $gallery->deleteFile();
        });
    }
}