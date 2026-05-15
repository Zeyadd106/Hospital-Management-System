<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VaccinationBooking;
use Illuminate\Auth\Access\HandlesAuthorization;

class VaccinationBookingPolicy
{
    use HandlesAuthorization;

    public function confirm(User $user, VaccinationBooking $booking)
    {
        return $user->id === $booking->user_id;
    }

    public function cancel(User $user, VaccinationBooking $booking)
    {
        return $user->id === $booking->user_id;
    }
}
