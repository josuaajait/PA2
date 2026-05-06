<?php

// app/Models/EmailOtpVerification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtpVerification extends Model
{
    protected $fillable = ['user_id', 'otp', 'expires_at', 'is_used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }
}