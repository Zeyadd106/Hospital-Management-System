<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'status',
        'department_id',
        'description'
    ];

    protected $attributes = [
        'status' => 'active',
        'description' => ''
    ];

    /**
     * Get the department that owns the clinic.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
