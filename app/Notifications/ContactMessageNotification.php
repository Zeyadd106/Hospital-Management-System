<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use App\Models\ContactMessage;

class ContactMessageNotification extends Notification
{
    use Queueable, SerializesModels;

    public $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Message Received')
            ->greeting('Hello Doctor,')
            ->line('You have received a new contact message:')
            ->line('Name: ' . $this->contactMessage->name)
            ->line('Email: ' . $this->contactMessage->email)
            ->line('Phone: ' . $this->contactMessage->phone)
            ->line('Subject: ' . $this->contactMessage->subject)
            ->line('Message: ' . $this->contactMessage->message)
            ->action('View Message', url('/admin/messages'))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'name' => $this->contactMessage->name,
            'email' => $this->contactMessage->email,
            'phone' => $this->contactMessage->phone,
            'subject' => $this->contactMessage->subject,
            'message' => $this->contactMessage->message,
        ];
    }
}
