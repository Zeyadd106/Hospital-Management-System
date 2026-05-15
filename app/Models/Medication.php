<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pharmacy;
use App\Models\PrescriptionRefill;
use App\Models\StockItem;

class Medication extends Model
{
    protected $fillable = [
        'name',
        'description',
        'manufacturer',
        'strength',
        'form',
        'quantity',
        'price',
        'expiry_date',
        'batch_number',
        'pharmacy_id',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'status' => 'boolean'
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function prescriptionRefills()
    {
        return $this->hasMany(PrescriptionRefill::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    public function stock()
    {
        return $this->hasMany(StockItem::class);
    }
}