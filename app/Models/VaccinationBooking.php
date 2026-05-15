<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vaccine_id',
        'clinic_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'confirmation_code',
        'status',
        'confirmed_at',
        'cancelled_at',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}