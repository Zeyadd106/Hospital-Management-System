<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'license_number',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }
}
