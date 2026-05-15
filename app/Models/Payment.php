<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'amount',
        'description',
        'status',
        'transaction_id',
        'appointment_id',
        'vaccination_booking_id',
        'health_screening_booking_id',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method associated with the payment.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the appointment associated with the payment.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the vaccination booking associated with the payment.
     */
    public function vaccinationBooking()
    {
        return $this->belongsTo(VaccinationBooking::class);
    }

    /**
     * Get the health screening booking associated with the payment.
     */
    public function healthScreeningBooking()
    {
        return $this->belongsTo(HealthScreeningBooking::class);
    }
}
