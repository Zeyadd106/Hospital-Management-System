<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicPatient extends Model
{
    protected $fillable = [
        'doctor_id', 
        'user_id', 
        'clinic_id', 
        'registration_date', 
        'status'
    ];

    protected $dates = ['registration_date'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
