<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'type',
        'title',
        'content',
        'is_read',
        'related_id',
        'notifiable_type',
        'notifiable_id',
        'data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    
    public function getIconClassAttribute()
    {
        switch ($this->type) {
            case 'message':
                return 'fas fa-envelope';
            case 'contact':
                return 'fas fa-paper-plane';
            case 'appointment':
                return 'fas fa-calendar-check';
            case 'system':
                return 'fas fa-bell';
            default:
                return 'fas fa-bell';
        }
    }
}
