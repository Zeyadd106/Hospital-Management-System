<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'manufacturer',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'expiry_date' => 'datetime',
        'status' => 'boolean'
    ];
}
