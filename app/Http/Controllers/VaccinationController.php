<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use App\Models\Notification;
use App\Models\Clinic;
use App\Models\VaccinationBooking;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    public function index()
    {
        try {
            $vaccines = Vaccine::where('is_available', true)->get();
            
            // Fetch user's vaccination bookings if authenticated
            $bookings = auth()->check() 
                ? VaccinationBooking::where('user_id', auth()->id())
                    ->with(['vaccine', 'clinic'])
                    ->orderBy('appointment_date', 'desc')
                    ->paginate(10)
                : collect([]); // Use an empty collection if not authenticated
            
            return view('vaccination.index', compact('vaccines', 'bookings'));
        } catch (\Exception $e) {
            Log::error('Error fetching vaccinations: ' . $e->getMessage());
            return view('vaccination.index', [
                'vaccines' => collect([]),
                'bookings' => collect([])
            ]);
        }
    }

    public function reminders()
    {
        try {
            $upcomingVaccinations = auth()->user()->vaccinationBookings()
                ->where('date', '>', now())
                ->orderBy('date')
                ->get();

            return view('vaccination.reminders', compact('upcomingVaccinations'));
        } catch (\Exception $e) {
            Log::error('Error fetching vaccination reminders: ' . $e->getMessage());
            return view('vaccination.reminders', [
                'upcomingVaccinations' => collect([])
            ]);
        }
    }

    public function sendReminder($id)
    {
        try {
            $vaccination = auth()->user()->vaccinationBookings()->findOrFail($id);
            
            // Send notification
            $notification = new Notification([
                'data' => [
                    'title' => 'Vaccination Reminder',
                    'message' => "You have a vaccination appointment scheduled for " . $vaccination->date . " at " . $vaccination->time,
                    'type' => 'vaccination',
                    'id' => $id
                ],
                'type' => 'App\Notifications\AppointmentReminder'
            ]);

            auth()->user()->notify($notification);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error sending vaccination reminder: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send reminder'], 500);
        }
    }

    public function show($id)
    {
        try {
            $vaccine = Vaccine::findOrFail($id);
            
            return view('vaccination.details', compact('vaccine'));
        } catch (\Exception $e) {
            Log::error('Error fetching vaccination details: ' . $e->getMessage());
            return redirect()->route('vaccination.index')
                ->with('error', 'Vaccination details could not be found.');
        }
    }

    public function create()
    {
        try {
            $vaccines = Vaccine::where('is_available', true)->get();
            $clinics = Clinic::all();
            
            return view('vaccination.create', compact('vaccines', 'clinics'));
        } catch (\Exception $e) {
            Log::error('Error loading vaccination create page: ' . $e->getMessage());
            return redirect()->route('vaccination.index')
                ->with('error', 'Unable to load vaccination booking page');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'vaccine_id' => 'required|exists:vaccines,id',
                'clinic_id' => 'required|exists:clinics,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => 'required',
            ]);

            $vaccine = Vaccine::findOrFail($request->vaccine_id);
            $clinic = Clinic::findOrFail($request->clinic_id);

            $booking = new VaccinationBooking();
            $booking->user_id = Auth::id();
            $booking->vaccine_id = $request->vaccine_id;
            $booking->clinic_id = $request->clinic_id;
            $booking->appointment_date = $request->appointment_date;
            $booking->appointment_time = $request->appointment_time;
            $booking->status = 'pending';
            $booking->confirmation_code = Str::random(8);
            $booking->save();

            return redirect()->route('vaccination.booking.show', $booking->id)
                ->with('success', 'Vaccination booking created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating vaccination booking: ' . $e->getMessage());
            return redirect()->route('vaccination.create')
                ->withInput()
                ->with('error', 'Failed to create vaccination booking');
        }
    }

    public function bookings()
    {
        $bookings = VaccinationBooking::where('user_id', Auth::id())
            ->with(['vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('vaccination.bookings.index', compact('bookings'));
    }

    public function showBooking($id)
    {
        $booking = VaccinationBooking::with(['vaccine', 'clinic'])
            ->findOrFail($id);
        
        $this->authorize('view', $booking);
        
        return view('vaccination.bookings.show', compact('booking'));
    }

    public function editBooking($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('update', $booking);

        $vaccines = Vaccine::where('is_available', true)->get();
        $clinics = Clinic::all();

        return view('vaccination.bookings.edit', compact('booking', 'vaccines', 'clinics'));
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('update', $booking);

        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $booking->vaccine_id = $request->vaccine_id;
        $booking->clinic_id = $request->clinic_id;
        $booking->appointment_date = $request->appointment_date;
        $booking->appointment_time = $request->appointment_time;
        $booking->save();

        return redirect()->route('vaccination.booking.show', $booking->id)
            ->with('success', 'Vaccination booking updated successfully');
    }

    public function cancel($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('cancel', $booking);

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('vaccination.bookings')
            ->with('success', 'Vaccination booking cancelled successfully');
    }
}