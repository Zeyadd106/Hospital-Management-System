<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\HealthScreeningBooking;
use App\Models\VaccinationBooking;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Link related bookings together
     * 
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking1
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking2
     * @return void
     */
    public function linkBookings($booking1, $booking2)
    {
        DB::transaction(function () use ($booking1, $booking2) {
            $booking1->linkBooking($booking2);
            $booking2->linkBooking($booking1);
        });
    }

    /**
     * Get all visible bookings for a user
     * 
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserVisibleBookings($userId)
    {
        $appointments = Appointment::where('user_id', $userId)
            ->visible()
            ->with('doctor', 'clinic')
            ->get();

        $healthScreenings = HealthScreeningBooking::where('user_id', $userId)
            ->visible()
            ->with('healthScreening', 'clinic', 'doctor')
            ->get();

        $vaccinations = VaccinationBooking::where('user_id', $userId)
            ->visible()
            ->with('vaccine', 'clinic', 'doctor')
            ->get();

        return collect($appointments)
            ->merge($healthScreenings)
            ->merge($vaccinations)
            ->sortBy('appointment_date');
    }

    /**
     * Complete the linked bookings transaction
     * 
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking1
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking2
     * @return void
     */
    public function completeLinkedBookings($booking1, $booking2)
    {
        DB::transaction(function () use ($booking1, $booking2) {
            // Update status to completed
            $booking1->status = 'completed';
            $booking2->status = 'completed';

            // Ensure both bookings remain invisible
            $booking1->is_visible = false;
            $booking2->is_visible = false;

            $booking1->save();
            $booking2->save();
        });
    }

    /**
     * Cancel linked bookings
     * 
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking1
     * @param Appointment|HealthScreeningBooking|VaccinationBooking $booking2
     * @param string $cancellationReason
     * @return void
     */
    public function cancelLinkedBookings($booking1, $booking2, $cancellationReason = 'Linked booking cancelled')
    {
        DB::transaction(function () use ($booking1, $booking2, $cancellationReason) {
            // Update status to cancelled
            $booking1->status = 'cancelled';
            $booking2->status = 'cancelled';

            // Add cancellation reason if applicable
            if (method_exists($booking1, 'setCancellationReason')) {
                $booking1->setCancellationReason($cancellationReason);
            }
            if (method_exists($booking2, 'setCancellationReason')) {
                $booking2->setCancellationReason($cancellationReason);
            }

            // Ensure both bookings remain invisible
            $booking1->is_visible = false;
            $booking2->is_visible = false;

            $booking1->save();
            $booking2->save();
        });
    }
}
