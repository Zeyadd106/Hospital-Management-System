<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'qualification',
        'experience_years',
        'consultation_fee',
        'about',
        'profile_image',
        'rating',
        'availability',
        'department_id',
        'is_available'
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'consultation_fee' => 'decimal:2',
        'rating' => 'decimal:2',
        'availability' => 'json',
        'is_available' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id')
            ->where('doctor_id', $this->user_id);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'user_id')
            ->where('doctor_id', $this->user_id);
    }
}
