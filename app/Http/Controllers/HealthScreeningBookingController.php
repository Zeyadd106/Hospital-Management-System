<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthScreeningBooking;
use App\Models\HealthScreening;
use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HealthScreeningBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $bookings = HealthScreeningBooking::where('user_id', Auth::id())
            ->with(['healthScreening', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('health-screenings.index', compact('bookings'));
    }

    public function create()
    {
        $screenings = HealthScreening::all();
        $clinics = Clinic::all();

        return view('health-screenings.create', compact('screenings', 'clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'health_screening_id' => 'required|exists:health_screenings,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $screening = HealthScreening::findOrFail($request->health_screening_id);
        $clinic = Clinic::findOrFail($request->clinic_id);

        $booking = new HealthScreeningBooking();
        $booking->user_id = Auth::id();
        $booking->health_screening_id = $request->health_screening_id;
        $booking->screening_type = $screening->name;
        $booking->clinic_id = $request->clinic_id;
        $booking->appointment_date = $request->appointment_date;
        $booking->appointment_time = $request->appointment_time;
        $booking->status = 'pending';
        $booking->confirmation_code = Str::random(8);
        $booking->save();

        return redirect()->route('health-screenings.booking.show', $booking->id)
            ->with('success', 'Health screening booking created successfully');
    }

    public function show($id)
    {
        $booking = HealthScreeningBooking::with(['healthScreening', 'clinic', 'user'])
            ->findOrFail($id);
        
        // Ensure the user can only view their own bookings
        $this->authorize('view', $booking);
        
        return view('health-screenings.bookings.show', [
            'booking' => $booking,
            'screeningName' => $booking->healthScreening->name,
            'clinicName' => $booking->clinic->name,
            'appointmentDate' => $booking->appointment_date,
            'appointmentTime' => $booking->appointment_time,
            'status' => $booking->status,
        ]);
    }

    public function edit($id)
    {
        $booking = HealthScreeningBooking::findOrFail($id);
        $this->authorize('update', $booking);

        $screenings = HealthScreening::all();
        $clinics = Clinic::all();

        return view('health-screenings.bookings.edit', compact('booking', 'screenings', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $booking = HealthScreeningBooking::findOrFail($id);
        $this->authorize('update', $booking);

        $request->validate([
            'health_screening_id' => 'required|exists:health_screenings,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $booking->health_screening_id = $request->health_screening_id;
        $booking->clinic_id = $request->clinic_id;
        $booking->appointment_date = $request->appointment_date;
        $booking->appointment_time = $request->appointment_time;
        $booking->save();

        return redirect()->route('health-screenings.show', $booking->id)
            ->with('success', 'Health screening booking updated successfully');
    }

    public function destroy($id)
    {
        try {
            $booking = HealthScreeningBooking::findOrFail($id);
            
            // Ensure the user can only delete their own bookings
            $this->authorize('delete', $booking);
            
            $booking->delete();
            
            return redirect()->route('health-screenings.booking.index')
                ->with('success', 'Health screening booking deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting health screening booking: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete health screening booking');
        }
    }

    public function verify(Request $request)
    {
        $booking = HealthScreeningBooking::where('confirmation_code', $request->code)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Invalid or expired verification code');
        }

        return view('health-screenings.booking.verify', compact('booking'));
    }

    public function confirm($id)
    {
        $booking = HealthScreeningBooking::findOrFail($id);
        $this->authorize('confirm', $booking);

        $booking->status = 'confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        return redirect()->route('health-screenings.booking.show', $booking->id)
            ->with('success', 'Health screening booking confirmed successfully');
    }

    public function cancel($id)
    {
        $booking = HealthScreeningBooking::findOrFail($id);
        $this->authorize('cancel', $booking);

        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->save();

        return redirect()->route('health-screenings.booking.show', $booking->id)
            ->with('success', 'Health screening booking cancelled successfully');
    }
}