<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\HealthScreeningBooking;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'clinic_id',
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'notes',
        'type',
        'is_confirmed',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    /**
     * Get the user (patient) that owns the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for the user relationship to make it clearer when referring to patients.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the doctor associated with the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')
            ->where('role', 'doctor');
    }

    /**
     * Get the clinic associated with the appointment.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the prescriptions associated with the appointment.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id');
    }

    /**
     * Get the health screening bookings associated with the appointment.
     */
    public function healthScreenings()
    {
        return $this->morphMany(HealthScreeningBooking::class, 'linked_booking');
    }

    /**
     * Check if the appointment can be cancelled.
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending' || $this->status === 'confirmed';
    }

    /**
     * Check if the appointment can be confirmed.
     */
    public function canBeConfirmed()
    {
        return $this->status === 'pending';
    }

    public function scopeWithDoctorProfile($query)
    {
        return $query->with(['doctor.doctorProfile']);
    }

    /**
     * Scope a query to only include appointments for a specific doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Get the status color for better visualization.
     */
    public function getStatusColor()
    {
        return match ($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            default => 'primary',
        };
    }

    /**
     * Get the doctor's name with title.
     */
    public function getDoctorName()
    {
        return $this->doctor ? 'Dr. ' . $this->doctor->name : 'Unknown Doctor';
    }
}