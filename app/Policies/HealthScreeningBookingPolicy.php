<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HealthScreeningBooking;
use Illuminate\Auth\Access\HandlesAuthorization;

class HealthScreeningBookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HealthScreeningBooking  $booking
     * @return mixed
     */
    public function delete(User $user, HealthScreeningBooking $booking)
    {
        // Users can only delete their own bookings
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HealthScreeningBooking  $booking
     * @return mixed
     */
    public function view(User $user, HealthScreeningBooking $booking)
    {
        // Users can only view their own bookings
        return $user->id === $booking->user_id;
    }
}
