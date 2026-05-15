<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'content',
        'is_from_user',
        'read_at'
    ];

    protected $casts = [
        'is_from_user' => 'boolean',
        'read_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')
            ->where('role', 'doctor');
    }

    // Add sender relationship
    public function sender()
    {
        return $this->is_from_user 
            ? $this->belongsTo(User::class, 'user_id') 
            : $this->belongsTo(User::class, 'doctor_id');
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeBetweenUsers($query, $userId, $doctorId)
    {
        return $query->where(function ($q) use ($userId, $doctorId) {
            $q->where('user_id', $userId)
              ->where('doctor_id', $doctorId);
        })->orWhere(function ($q) use ($userId, $doctorId) {
            $q->where('user_id', $doctorId)
              ->where('doctor_id', $userId);
        });
    }
}