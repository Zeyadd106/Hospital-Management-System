<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaccinationBooking;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VaccinationBookingController extends Controller
{
    /**
     * Display a listing of the vaccination bookings.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $bookings = VaccinationBooking::with('user')->latest()->paginate(10);
        return view('admin.vaccinations.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new vaccination booking.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $users = User::all();
        $vaccines = Vaccine::where('status', 'available')->get();
        return view('admin.vaccinations.create', compact('users', 'vaccines'));
    }

    /**
     * Store a newly created vaccination booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Vaccination Booking Request Data:', [
            'user_id' => $request->user_id,
            'vaccination_type' => $request->vaccination_type,
            'appointment_date' => $request->appointment_date,
            'vaccine_id' => $request->vaccine_id
        ]);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'vaccination_type' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'vaccine_id' => 'nullable|exists:vaccines,id'
        ]);

        // Find the vaccine to ensure the type is valid
        $vaccine = Vaccine::where('name', $request->vaccination_type)->first();
        if (!$vaccine) {
            return back()->withErrors([
                'vaccination_type' => 'Invalid vaccination type selected.'
            ])->withInput();
        }

        $booking = new VaccinationBooking();
        $booking->user_id = $request->user_id;
        $booking->vaccination_type = $vaccine->name; // Use the exact name from the database
        $booking->appointment_date = $request->appointment_date;
        $booking->vaccine_id = $vaccine->id;
        $booking->confirmation_code = Str::random(10);
        
        try {
            $booking->save();
            return redirect()->route('admin.vaccinations.index')
                ->with('success', 'Vaccination booking created successfully.');
        } catch (\Exception $e) {
            Log::error('Vaccination Booking Save Error: ' . $e->getMessage());
            return back()->withErrors([
                'general' => 'Failed to save vaccination booking. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Display the specified vaccination booking.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $booking = VaccinationBooking::with('user')->findOrFail($id);
        return view('admin.vaccinations.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified vaccination booking.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $users = User::all();
        $vaccines = Vaccine::where('status', 'available')->get();
        return view('admin.vaccinations.edit', compact('booking', 'users', 'vaccines'));
    }

    /**
     * Update the specified vaccination booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $booking = VaccinationBooking::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vaccination_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->user_id = $request->user_id;
        $booking->vaccination_type = $request->vaccination_type;
        $booking->appointment_date = $request->appointment_date;
        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination booking updated successfully.');
    }

    /**
     * Remove the specified vaccination booking from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $booking = VaccinationBooking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination booking deleted successfully.');
    }
}
