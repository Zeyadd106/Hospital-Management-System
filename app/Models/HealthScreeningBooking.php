<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthScreeningBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'health_screening_id',
        'clinic_id',
        'doctor_id',
        'screening_type',
        'appointment_date',
        'appointment_time',
        'confirmation_code',
        'status',
        'confirmed_at',
        'cancelled_at',
        'completed_at',
        'notes',
        'linked_booking_id',
        'linked_booking_type',
        'is_visible'
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

    public function healthScreening()
    {
        return $this->belongsTo(HealthScreening::class);
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

    /**
     * Get the linked booking
     */
    public function linkedBooking()
    {
        return $this->morphTo('linked_booking');
    }

    /**
     * Link this health screening booking to another booking
     * 
     * @param Model $booking
     * @return void
     */
    public function linkBooking($booking)
    {
        $this->linked_booking_id = $booking->id;
        $this->linked_booking_type = get_class($booking);
        $this->save();

        // Make both bookings invisible
        $this->is_visible = false;
        $booking->is_visible = false;
        $booking->save();
    }

    /**
     * Scope a query to only include visible bookings
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}