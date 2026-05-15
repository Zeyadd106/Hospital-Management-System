<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class CustomResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        Log::info('Password Reset Notification Channels', [
            'notifiable_email' => $notifiable->email,
            'notifiable_id' => $notifiable->id,
        ]);
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            $resetUrl = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            Log::info('Password Reset URL Generated', [
                'reset_url' => $resetUrl,
                'user_email' => $notifiable->email,
            ]);

            return (new MailMessage)
                ->subject(Lang::get('Medicare Password Reset'))
                ->greeting('Hello!')
                ->line(Lang::get('You are receiving this email because we received a password reset request for your Medicare account.'))
                ->action(Lang::get('Reset Password'), $resetUrl)
                ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')]))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'))
                ->salutation('Best regards,\nMedicare Support Team');
        } catch (\Exception $e) {
            Log::error('Password Reset Notification Error', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_email' => $notifiable->email,
            ]);

            throw $e;
        }
    }
}
