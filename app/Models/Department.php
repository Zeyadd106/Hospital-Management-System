<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status'
    ];

    /**
     * Get the clinics for the department.
     */
    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }

    /**
     * Get the doctors for the department.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}

