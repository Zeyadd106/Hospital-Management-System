<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'gender',
        'age',
        'address',
        'medical_history',
        'emergency_contact',
        'emergency_phone',
        'blood_type',
        'notes'
    ];

    protected $casts = [
        'age' => 'integer',
        'medical_history' => 'array',
        'notes' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
