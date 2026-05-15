<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthScreening;
use App\Models\Vaccine;
use App\Models\HealthScreeningBooking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HealthScreeningController extends Controller
{
    public function index()
    {
        try {
            $healthScreenings = HealthScreening::where('is_available', true)->get();
            $vaccines = Vaccine::where('is_available', true)->get();
            
            // Fetch user's health screening bookings if authenticated
            $bookings = auth()->check() 
                ? HealthScreeningBooking::where('user_id', auth()->id())
                    ->with(['healthScreening', 'clinic'])
                    ->orderBy('appointment_date', 'desc')
                    ->paginate(10)
                : collect([]); // Use an empty collection if not authenticated
            
            return view('health-screenings.index', compact('healthScreenings', 'vaccines', 'bookings'));
        } catch (\Exception $e) {
            Log::error('Error fetching health screenings: ' . $e->getMessage());
            return view('health-screenings.index', [
                'healthScreenings' => collect([]),
                'vaccines' => collect([]),
                'bookings' => collect([])
            ]);
        }
    }

    public function show($id)
    {
        try {
            $healthScreening = HealthScreening::findOrFail($id);
            return view('health-screenings.show', compact('healthScreening'));
        } catch (\Exception $e) {
            Log::error('Error fetching health screening details: ' . $e->getMessage());
            return redirect()->route('health-screenings.index')
                           ->with('error', 'Health screening not found.');
        }
    }

    public function reminders()
    {
        try {
            $upcomingScreenings = auth()->user()->healthScreeningBookings()
                ->where('date', '>', now())
                ->orderBy('date')
                ->get();

            return view('health-screenings.reminders', compact('upcomingScreenings'));
        } catch (\Exception $e) {
            Log::error('Error fetching health screening reminders: ' . $e->getMessage());
            return view('health-screenings.reminders', [
                'upcomingScreenings' => collect([])
            ]);
        }
    }

    public function sendReminder($id)
    {
        try {
            $screening = auth()->user()->healthScreeningBookings()->findOrFail($id);
            
            // Send notification
            $notification = new \App\Models\Notification([
                'data' => [
                    'title' => 'Health Screening Reminder',
                    'message' => "You have a health screening appointment scheduled for " . $screening->date . " at " . $screening->time,
                    'type' => 'health-screening',
                    'id' => $id
                ],
                'type' => 'App\Notifications\AppointmentReminder'
            ]);

            auth()->user()->notify($notification);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error sending health screening reminder: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send reminder'], 500);
        }
    }

    public function edit()
    {
        try {
            $healthScreenings = HealthScreening::where('is_available', true)->get();
            $vaccines = Vaccine::where('is_available', true)->get();
            
            // Fetch user's health screening bookings if authenticated
            $bookings = auth()->check() 
                ? HealthScreeningBooking::where('user_id', auth()->id())
                    ->with(['healthScreening', 'clinic'])
                    ->orderBy('appointment_date', 'desc')
                    ->paginate(10)
                : collect([]); // Use an empty collection if not authenticated
            
            return view('health-screenings.edit', compact('healthScreenings', 'vaccines', 'bookings'));
        } catch (\Exception $e) {
            Log::error('Error in health screenings edit: ' . $e->getMessage());
            return redirect()->route('health-screenings.index')
                ->with('error', 'An error occurred while loading health screenings.');
        }
    }
}
