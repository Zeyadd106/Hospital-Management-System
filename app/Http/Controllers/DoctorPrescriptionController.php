<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorPrescriptionController extends Controller
{
    /**
     * Display a listing of the prescriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prescriptions = Auth::user()->doctorPrescriptions()
            ->with('patient') // Eager load the patient relationship
            ->latest()
            ->paginate(10);
            
        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new prescription.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patients = Patient::where('doctor_id', Auth::id())->get();
        return view('doctor.prescriptions.create', compact('patients'));
    }

    /**
     * Store a newly created prescription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medications' => 'required|array',
            'instructions' => 'required|string',
            'valid_until' => 'required|date|after:today',
        ]);

        $prescription = Prescription::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $validated['patient_id'],
            'instructions' => $validated['instructions'],
            'valid_until' => $validated['valid_until'],
        ]);

        // Store medications
        foreach ($validated['medications'] as $medication) {
            $prescription->medications()->create($medication);
        }

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription created successfully');
    }

    /**
     * Display the specified prescription.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription)
    {
        return view('doctor.prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified prescription.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription)
    {
        $patients = Patient::where('doctor_id', Auth::id())->get();
        return view('doctor.prescriptions.edit', compact('prescription', 'patients'));
    }

    /**
     * Update the specified prescription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medications' => 'required|array',
            'instructions' => 'required|string',
            'valid_until' => 'required|date|after:today',
        ]);

        $prescription->update([
            'patient_id' => $validated['patient_id'],
            'instructions' => $validated['instructions'],
            'valid_until' => $validated['valid_until'],
        ]);

        // Update medications
        $prescription->medications()->delete();
        foreach ($validated['medications'] as $medication) {
            $prescription->medications()->create($medication);
        }

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription updated successfully');
    }

    /**
     * Remove the specified prescription from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription deleted successfully');
    }
}
