<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGallery;

class Promo extends Model
{
    use HasGallery; // Tambahkan ini
    
    protected $fillable = [
        'title', 'slug', 'description', 'discount_type',
        'discount_value', 'promo_code', 'promo_type', 
        'min_purchase', 'max_discount', 'start_date', 'end_date',
        'applicable_for', 'max_usage', 'used_count', 'is_active'
        // Hapus 'banner_image' dari fillable
    ];
    
    // ... kode lainnya
}