<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationManagementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'medications',
        'services',
        'special_requests',
        'status',
        'confirmation_code'
    ];

    protected $casts = [
        'services' => 'array',
    ];

    /**
     * Get the user that owns the medication management request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

