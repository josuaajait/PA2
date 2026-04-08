<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reservation_date',
        'reservation_time',
        'number_of_guests',
        'table_numbers',
        'special_requests',
        'status',
        'down_payment',
        'payment_status',
        'payment_proof',
        'cancellation_reason',
        'confirmed_at',
        'cancelled_at',
        'user_id'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime',
        'table_numbers' => 'array',
        'down_payment' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Generate booking code otomatis
        static::creating(function ($reservation) {
            $reservation->booking_code = 'REST-' . strtoupper(uniqid());
        });
    }

    /**
     * Relasi ke User (customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relasi ke payments
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Scope berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope berdasarkan tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('reservation_date', [$startDate, $endDate]);
    }

    /**
     * Cek ketersediaan meja
     */
    public static function checkAvailability($date, $time, $guests)
    {
        // Kapasitas total restoran
        $totalCapacity = 100;
        
        // Total tamu yang sudah reservasi
        $existingReservations = self::where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('number_of_guests');
        
        $available = ($totalCapacity - $existingReservations) >= $guests;
        
        return [
            'available' => $available,
            'capacity' => $totalCapacity,
            'booked' => $existingReservations,
            'remaining' => $totalCapacity - $existingReservations
        ];
    }

    /**
     * Confirm reservation
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
    }

    /**
     * Cancel reservation
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);
    }

    /**
     * Complete reservation
     */
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-success">Confirmed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'completed' => '<span class="badge bg-info">Completed</span>'
        ];
        
        return $labels[$this->status] ?? '<span class="badge bg-secondary">' . $this->status . '</span>';
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
            'partial' => '<span class="badge bg-warning">Partial (DP)</span>',
            'paid' => '<span class="badge bg-success">Paid</span>'
        ];
        
        return $labels[$this->payment_status] ?? '<span class="badge bg-secondary">' . $this->payment_status . '</span>';
    }
}