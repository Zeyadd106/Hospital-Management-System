<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_item_id',
        'user_id',
        'adjustment_type',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'reason',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the stock item that owns the adjustment.
     */
    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }

    /**
     * Get the user who made the adjustment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the adjustment type in a human-readable format.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return ucfirst($this->adjustment_type);
    }

    /**
     * Scope a query to only include adjustments for a specific stock item.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $stockItemId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForStockItem($query, $stockItemId)
    {
        return $query->where('stock_item_id', $stockItemId);
    }

    /**
     * Scope a query to only include adjustments of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('adjustment_type', $type);
    }

    /**
     * Scope a query to only include adjustments made by a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
