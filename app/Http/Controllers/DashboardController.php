<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\VaccinationBooking;
use App\Models\HealthScreeningBooking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's upcoming appointments
        $upcomingAppointments = Appointment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('appointment_date')
            ->with('doctor')
            ->take(3)
            ->get();

        // Get user's vaccination bookings
        $vaccinationBookings = VaccinationBooking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get user's health screening bookings
        $healthScreeningBookings = HealthScreeningBooking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard.user', compact(
            'user', 
            'upcomingAppointments', 
            'vaccinationBookings', 
            'healthScreeningBookings'
        ));
    }
}
