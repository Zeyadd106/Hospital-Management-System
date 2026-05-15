<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthScreening extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'category',
        'recommended_age_group',
        'icon',
        'is_available'
    ];

    public function bookings()
    {
        return $this->hasMany(HealthScreeningBooking::class, 'screening_id');
    }
}

