<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'title',
        'findings',
        'diagnosis',
        'treatment_plan',
        'medications',
        'follow_up_needed',
        'status',
        'notes'
    ];

    protected $casts = [
        'follow_up_needed' => 'boolean',
        'medications' => 'array',
        'notes' => 'array'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
