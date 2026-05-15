<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'pharmacy_id',
        'amount',
        'payment_method',
        'status',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
