<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'payable_type',
        'payable_id',
        'amount',
        'payment_type',
        'payment_method',
        'bank_name',
        'account_number',
        'account_name',
        'payment_proof',
        'payment_status',
        'notes',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            $payment->payment_code = 'PAY-' . strtoupper(uniqid());
        });
    }

    /**
     * Polymorphic relasi
     * Bisa ke TableReservation atau PoolTicket
     */
    public function payable()
    {
        return $this->morphTo();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('payment_status', 'verified');
    }

    public function verify($adminId)
    {
        $this->update([
            'payment_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $adminId
        ]);
        
        // Update status pada payable (TableReservation atau PoolTicket)
        $this->payable->update(['payment_status' => 'paid']);
    }

    public function reject($adminId, $reason = null)
    {
        $this->update([
            'payment_status' => 'rejected',
            'notes' => $reason,
            'verified_at' => now(),
            'verified_by' => $adminId
        ]);
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'e_wallet' => 'E-Wallet'
        ];
        
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'verified' => '<span class="badge bg-success">Verified</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>'
        ];
        
        return $labels[$this->payment_status] ?? '<span class="badge bg-secondary">' . $this->payment_status . '</span>';
    }
}