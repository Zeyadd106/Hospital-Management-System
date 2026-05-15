<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Appointment;

class AppointmentConfirmedNotification extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Appointment Confirmed')
                    ->line('Your appointment has been confirmed.')
                    ->line('Appointment Details:')
                    ->line('Date: ' . $this->appointment->appointment_date->format('M d, Y'))
                    ->line('Time: ' . $this->appointment->appointment_time->format('H:i'))
                    ->line('Doctor: ' . $this->appointment->doctor->name)
                    ->action('View Appointment', url('/appointments/' . $this->appointment->id))
                    ->line('Thank you for using our application!');
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
            'doctor_name' => $this->appointment->doctor->name,
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time->format('H:i'),
            'message' => sprintf(
                'Your appointment with Dr. %s on %s at %s has been confirmed.',
                $this->appointment->doctor->name,
                $this->appointment->appointment_date->format('M d, Y'),
                $this->appointment->appointment_time->format('H:i')
            ),
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'database' => 'notifications',
            'mail' => 'emails',
        ];
    }
}
