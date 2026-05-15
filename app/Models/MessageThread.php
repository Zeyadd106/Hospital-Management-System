<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'last_message_at'
    ];

    protected $dates = [
        'last_message_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id');
    }
    
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }
    
    public function unreadCount()
    {
        return $this->messages()
            ->where('is_read', false)
            ->where('sender_type', 'doctor')
            ->count();
    }
}

