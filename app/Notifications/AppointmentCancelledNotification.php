<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentCancelledNotification extends Notification
{
    use Queueable;

    /**
     * The appointment instance.
     *
     * @var \App\Models\Appointment
     */
    public $appointment;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->user->name,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
            'message' => 'A patient has cancelled their appointment with you.',
            'type' => 'appointment_cancelled',
        ];
    }
}
