<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'replied_at',
        'replied_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}
