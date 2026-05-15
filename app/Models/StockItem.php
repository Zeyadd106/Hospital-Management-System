<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'medication_id',
        'current_stock',
        'min_stock',
        'last_updated_at'
    ];

    protected $casts = [
        'last_updated_at' => 'datetime'
    ];

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Get all adjustments for the stock item.
     */
    public function adjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
