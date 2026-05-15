<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use App\Models\HealthScreeningBooking;
use App\Models\HealthScreening;
use App\Models\Clinic;

class HealthScreeningBookingNotification
{
    use Queueable, SerializesModels;

    public $booking;
    public $screening;
    public $clinic;

    public function __construct(HealthScreeningBooking $booking, HealthScreening $screening, Clinic $clinic)
    {
        $this->booking = $booking;
        $this->screening = $screening;
        $this->clinic = $clinic;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'health-screenings.verify',
            now()->addMinutes(30),
            ['code' => $this->booking->confirmation_code]
        );

        return (new MailMessage)
            ->subject('Health Screening Booking Confirmation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your health screening booking has been successfully created!')
            ->line('Here are your booking details:')
            ->line('Screening Type: ' . $this->screening->name)
            ->line('Clinic: ' . $this->clinic->name)
            ->line('Appointment Date: ' . $this->booking->appointment_date->format('F j, Y'))
            ->line('Appointment Time: ' . $this->booking->appointment_time)
            ->line('Confirmation Code: ' . $this->booking->confirmation_code)
            ->action('Verify Booking', $verificationUrl)
            ->line('Please keep this confirmation code safe as you will need it for verification.');
    }
}
