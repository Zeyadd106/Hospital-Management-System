<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class ClinicController extends Controller
{
    /**
     * Display a listing of the clinics.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $clinics = Clinic::with('department')->latest()->paginate(10);
        return view('admin.clinics.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new clinic.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $departments = Department::where('status', 'active')->get();
        return view('admin.clinics.create', compact('departments'));
    }

    /**
     * Store a newly created clinic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'phone' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $clinic = new Clinic();
        $clinic->name = $request->name;
        $clinic->description = $request->description;
        $clinic->location = $request->location;
        $clinic->phone = $request->phone;
        $clinic->department_id = $request->department_id;
        $clinic->status = $request->status;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('clinics', 'public');
            $clinic->image = $imagePath;
        }
        
        $clinic->save();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic created successfully.');
    }

    /**
     * Show the form for editing the specified clinic.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        $departments = Department::where('status', 'active')->get();
        return view('admin.clinics.edit', compact('clinic', 'departments'));
    }

    /**
     * Update the specified clinic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $clinic->name = $request->name;
        $clinic->description = $request->description;
        $clinic->department_id = $request->department_id;
        $clinic->status = $request->status;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($clinic->image) {
                Storage::disk('public')->delete($clinic->image);
            }
            
            $imagePath = $request->file('image')->store('clinics', 'public');
            $clinic->image = $imagePath;
        }
        
        $clinic->save();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic updated successfully.');
    }

    /**
     * Remove the specified clinic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        
        // Delete clinic image if exists
        if ($clinic->image) {
            Storage::disk('public')->delete($clinic->image);
        }
        
        $clinic->delete();

        return redirect()->route('admin.clinics.index')
            ->with('success', 'Clinic deleted successfully.');
    }
}
