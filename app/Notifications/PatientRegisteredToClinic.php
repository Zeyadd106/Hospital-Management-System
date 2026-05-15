<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PatientRegisteredToClinic extends Notification
{
    use Queueable;

    protected $doctor;
    protected $clinic;

    public function __construct($doctor, $clinic)
    {
        $this->doctor = $doctor;
        $this->clinic = $clinic;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Registered to New Clinic')
            ->line("You have been registered to {$this->clinic->name} clinic.")
            ->line("Doctor: {$this->doctor->name}")
            ->action('View Clinic Profile', route('patient.clinic.show', $this->clinic->id))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'doctor_name' => $this->doctor->name,
            'clinic_name' => $this->clinic->name,
            'message' => "You have been registered to {$this->clinic->name} clinic by Dr. {$this->doctor->name}."
        ];
    }
}
