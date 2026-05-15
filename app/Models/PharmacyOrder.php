<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'payment_id',
        'total_amount',
        'delivery_address',
        'notes',
        'status',
        'tracking_number',
        'delivered_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment associated with the order.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(PharmacyOrderItem::class, 'order_id');
    }

    /**
     * Check if the order is paid.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->payment && $this->payment->status === 'completed';
    }

    /**
     * Check if the order is delivered.
     *
     * @return bool
     */
    public function isDelivered()
    {
        return $this->status === 'delivered' && $this->delivered_at !== null;
    }

    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'processing' => '<span class="badge bg-info">Processing</span>',
            'shipped' => '<span class="badge bg-primary">Shipped</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
