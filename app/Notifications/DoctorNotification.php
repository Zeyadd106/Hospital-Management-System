<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DoctorNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $doctorId;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param int $doctorId
     * @return void
     */
    public function __construct($title, $message, $doctorId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->doctorId = $doctorId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->subject($this->title)
                    ->line($this->message)
                    ->action('View Details', url('/notifications'))
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
            'title' => $this->title,
            'message' => $this->message,
            'doctor_id' => $this->doctorId,
            'type' => 'doctor_notification'
        ];
    }
}
