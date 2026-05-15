<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Models\Medication;
use App\Models\User;

class PrescriptionRefill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prescription_id',
        'status',
        'notes',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the prescription refill.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prescription that owns the refill.
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
