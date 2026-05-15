<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'instructions',
        'valid_until'
    ];

    protected $casts = [
        'valid_until' => 'date',
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

    public function medications()
    {
        return $this->hasMany(PrescriptionMedication::class);
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', now()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now()->toDateString());
    }
}
