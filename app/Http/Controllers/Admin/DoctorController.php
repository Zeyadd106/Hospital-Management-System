<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $doctors = Doctor::latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $departments = Department::where('status', 'active')->get();
        return view('admin.doctors.create', compact('departments'));
    }

    /**
     * Store a newly created doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:doctors',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'working_hours' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $doctor = new Doctor();
        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->phone = $request->phone;
        $doctor->department = $request->department;
        $doctor->specialization = $request->specialization;
        $doctor->bio = $request->bio;
        $doctor->working_hours = $request->working_hours;
        $doctor->status = $request->status;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
        }
        
        $doctor->save();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    /**
     * Show the form for editing the specified doctor.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $departments = Department::where('status', 'active')->get();
        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    /**
     * Update the specified doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('doctors')->ignore($doctor->id),
            ],
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'working_hours' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->phone = $request->phone;
        $doctor->department = $request->department;
        $doctor->specialization = $request->specialization;
        $doctor->bio = $request->bio;
        $doctor->working_hours = $request->working_hours;
        $doctor->status = $request->status;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }
            
            $imagePath = $request->file('image')->store('doctors', 'public');
            $doctor->image = $imagePath;
        }
        
        $doctor->save();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        
        // Delete doctor image if exists
        if ($doctor->image) {
            Storage::disk('public')->delete($doctor->image);
        }
        
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}

