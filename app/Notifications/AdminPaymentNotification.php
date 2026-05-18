<?php
// app/Notifications/AdminPaymentNotification.php

namespace App\Notifications;

use App\Models\TableReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminPaymentNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(TableReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'admin_payment_uploaded',
            'booking_code' => $this->reservation->booking_code,
            'title' => '💰 Bukti Pembayaran Baru!',
            'body' => 'Customer ' . $this->reservation->customer_name . ' telah mengupload bukti pembayaran untuk reservasi ' . $this->reservation->booking_code . '. Silakan verifikasi.',
            'icon' => 'fa-upload',
            'color' => 'warning',
            'url' => route('admin.reservations.show', $this->reservation),
        ];
    }
}