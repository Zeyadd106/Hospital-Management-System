<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentVerifiedNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Verification Notification')
            ->greeting('Hello Doctor,')
            ->line('A patient has verified their appointment with you.')
            ->line('Appointment Details:')
            ->line('Date: ' . $this->appointment->appointment_date->format('F j, Y'))
            ->line('Time: ' . $this->appointment->appointment_time->format('g:i A'))
            ->line('Patient: ' . $this->appointment->user->name)
            ->line('Purpose: ' . $this->appointment->purpose)
            ->action('View Appointment', url('/doctor/appointments/' . $this->appointment->id))
            ->line('Thank you for using our healthcare system!');
    }
}
