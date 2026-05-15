<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaccinationBooking;
use App\Models\Vaccine;
use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VaccinationBookingVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function verify(Request $request)
    {
        $booking = VaccinationBooking::where('confirmation_code', $request->code)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Invalid or expired verification code');
        }

        return view('vaccination.bookings.verify', compact('booking'));
    }

    public function confirm($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('confirm', $booking);

        $booking->status = 'confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        return redirect()->route('vaccination.booking.show', $booking->id)
            ->with('success', 'Vaccination booking confirmed successfully');
    }

    public function cancel($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('cancel', $booking);

        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->save();

        return redirect()->route('vaccination.booking.show', $booking->id)
            ->with('success', 'Vaccination booking cancelled successfully');
    }
}

class VaccinationBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $bookings = VaccinationBooking::where('user_id', Auth::id())
            ->with(['vaccine', 'clinic'])  // Eager load related models for efficiency
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('vaccination.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $vaccines = Vaccine::all();
        $clinics = Clinic::all();
        return view('vaccination.create', compact('vaccines', 'clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vaccine_id' => 'required|exists:vaccines,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $vaccine = Vaccine::findOrFail($request->vaccine_id);

        $booking = new VaccinationBooking();
        $booking->user_id = Auth::id();
        $booking->vaccine_id = $request->vaccine_id;
        $booking->clinic_id = $request->clinic_id;
        $booking->appointment_date = $request->appointment_date;
        $booking->appointment_time = $request->appointment_time;
        $booking->status = 'pending';
        $booking->confirmation_code = Str::random(8);
        
        // Set vaccination type based on the vaccine
        $booking->vaccination_type = $this->mapVaccinationType($vaccine->name);
        
        $booking->save();

        // Determine the appropriate redirect route
        $redirectRoute = 'vaccination.booking.index';
        
        // Check if the request came from a specific route
        if ($request->route()->getName() === 'vaccination.book.store') {
            $redirectRoute = 'profile.index';
        }

        // Redirect to the appropriate route
        return redirect()->route($redirectRoute)
            ->with('success', 'Vaccination booking created successfully');
    }

    /**
     * Map vaccine name to vaccination type
     * 
     * @param string $vaccineName
     * @return string
     */
    private function mapVaccinationType(string $vaccineName): string
    {
        $typeMap = [
            'Flu' => 'Flu',
            'COVID-19' => 'COVID-19',
            'HPV' => 'HPV',
            'Childhood' => 'Childhood',
            'Travel' => 'Travel'
        ];

        return $typeMap[$vaccineName] ?? 'Other';
    }

    public function show($id)
    {
        $booking = VaccinationBooking::with(['vaccine', 'clinic', 'user'])
            ->findOrFail($id);
        
        // Ensure the user can only view their own bookings
        $this->authorize('view', $booking);
        
        return view('vaccination.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $vaccines = Vaccine::all();
        $clinics = Clinic::all();
        return view('vaccination.bookings.edit', compact('booking', 'vaccines', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        
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

    public function verify(Request $request)
    {
        $booking = VaccinationBooking::where('confirmation_code', $request->code)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Invalid or expired verification code');
        }

        return view('vaccination.bookings.verify', compact('booking'));
    }

    public function confirm($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('confirm', $booking);

        $booking->status = 'confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        return redirect()->route('vaccination.booking.show', $booking->id)
            ->with('success', 'Vaccination booking confirmed successfully');
    }

    public function cancel($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $this->authorize('cancel', $booking);

        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->save();

        return redirect()->route('vaccination.booking.show', $booking->id)
            ->with('success', 'Vaccination booking cancelled successfully');
    }

    /**
     * Cancel a vaccination booking
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $booking = VaccinationBooking::findOrFail($id);

        // Ensure the user can only cancel their own bookings
        if ($booking->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to cancel this booking.');
        }

        // Only allow cancellation of pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bookings can be cancelled.');
        }

        // Update booking status to cancelled
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('profile.index')
            ->with('success', 'Vaccination booking cancelled successfully.');
    }

    public function userShow($id)
    {
        $booking = VaccinationBooking::where('user_id', Auth::id())
            ->with(['vaccine', 'clinic'])
            ->findOrFail($id);

        return view('user.vaccinations.show', compact('booking'));
    }

    public function userIndex()
    {
        $bookings = VaccinationBooking::where('user_id', Auth::id())
            ->with(['vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('user.vaccinations.index', compact('bookings'));
    }
}