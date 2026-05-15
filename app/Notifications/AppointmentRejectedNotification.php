<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AppointmentRejectedNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Rejected')
            ->line("Your appointment with Dr. " . optional($this->appointment->doctor)->name . " has been rejected.")
            ->line("Date: " . optional($this->appointment->appointment_date)->format('M d, Y'))
            ->line("Time: " . $this->appointment->appointment_time)
            ->line("Reason: " . ($this->appointment->rejection_reason ?? 'No reason provided'))
            ->action('View Appointments', route('patient.appointments.index'))
            ->line('We apologize for any inconvenience.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_id' => $this->appointment->doctor_id,
            'doctor_name' => $this->appointment->doctor->name,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'rejection_reason' => $this->appointment->rejection_reason,
            'message' => "Your appointment with Dr. {$this->appointment->doctor->name} has been rejected."
        ];
    }
}
