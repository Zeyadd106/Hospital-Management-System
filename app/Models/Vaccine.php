<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer',
        'description',
        'recommended_age',
        'doses_required',
        'price',
        'status'
    ];

    /**
     * Get the appointments for the vaccine.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

