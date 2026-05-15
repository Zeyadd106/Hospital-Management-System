<?php

namespace App\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CustomDatabaseChannel extends BaseDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database', $notification)->create(
            $this->buildPayload($notifiable, $notification)
        );
    }

    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, $notification)
    {
        $data = $this->getData($notifiable, $notification);
        
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        }

        return [
            'id' => (string) Str::uuid(),
            'type' => get_class($notification),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'data' => $data,
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
