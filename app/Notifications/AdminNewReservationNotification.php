<?php

namespace App\Notifications;

use App\Models\TableReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNewReservationNotification extends Notification
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
            'title' => 'Reservasi Baru!',
            'body' => "Reservasi baru dari {$this->reservation->customer_name} untuk tanggal {$this->reservation->reservation_date} pukul {$this->reservation->reservation_time}",
            'icon' => 'fa-calendar-plus',
            'color' => 'primary',
            'reservation_id' => $this->reservation->id,
            'booking_code' => $this->reservation->booking_code,
            'customer_name' => $this->reservation->customer_name,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'number_of_guests' => $this->reservation->number_of_guests,
        ];
    }
}