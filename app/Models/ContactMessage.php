<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'doctor_id',
        'subject',
        'message',
        'status',
        'reply',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Get the doctor that owns the contact message.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

