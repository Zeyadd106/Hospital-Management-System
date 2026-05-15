<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'bio',
        'image',
        'is_available',
        'department_id',
        'clinic_id',
        'user_id'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the user associated with the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the doctor belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the clinic that the doctor belongs to.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the messages for the doctor.
     */
    public function messages()
    {
        return $this->hasMany(ContactMessage::class);
    }
}
