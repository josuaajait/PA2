<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGallery;

class Testimonial extends Model
{
    use HasGallery; // Tambahkan ini
    
    protected $fillable = [
        'customer_name', 'customer_email', 'rating',
        'comment', 'service_type', 'visit_date', 'is_approved',
        'is_featured', 'approved_at', 'approved_by'
        // Hapus 'customer_photo' dari fillable
    ];
    
    // ... kode lainnya
}