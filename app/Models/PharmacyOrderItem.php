<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyOrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'medication_id',
        'quantity',
        'price',
        'subtotal'
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(PharmacyOrder::class, 'order_id');
    }

    /**
     * Get the medication that the item represents.
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
