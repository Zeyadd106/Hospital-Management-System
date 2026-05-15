<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaccinationBooking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the vaccination bookings.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $vaccinations = VaccinationBooking::with('user')->latest()->paginate(10);
        return view('admin.vaccinations.index', compact('vaccinations'));
    }

    /**
     * Show the form for creating a new vaccination booking.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('admin.vaccinations.create');
    }

    /**
     * Store a newly created vaccination booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vaccination_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        VaccinationBooking::create($request->all());

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination booking created successfully.');
    }

    /**
     * Display the specified vaccination booking.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $vaccination = VaccinationBooking::with('user')->findOrFail($id);
        return view('admin.vaccinations.show', compact('vaccination'));
    }

    /**
     * Show the form for editing the specified vaccination booking.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $vaccination = VaccinationBooking::findOrFail($id);
        return view('admin.vaccinations.edit', compact('vaccination'));
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
        $vaccination = VaccinationBooking::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vaccination_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $vaccination->update($request->all());

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
        $vaccination = VaccinationBooking::findOrFail($id);
        $vaccination->delete();

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination booking deleted successfully.');
    }
}