<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\PrescriptionRefill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPharmacyController extends Controller
{
    public function listMedications(Request $request)
    {
        $query = Medication::with('stock')->where('is_active', true);
        
        // Apply filters
        if ($request->has('requires_prescription') && $request->requires_prescription !== '') {
            $query->where('requires_prescription', $request->requires_prescription);
        }
        
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->has('in_stock') && $request->in_stock === 'true') {
            $query->where('in_stock', true);
        }
        
        $medications = $query->paginate(10);
        
        return view('user.pharmacy.medications.index', compact('medications'));
    }

    public function searchMedications(Request $request)
    {
        $query = Medication::with('stock')->where('is_active', true);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
        
        $medications = $query->paginate(10);
        return view('user.pharmacy.medications.search', compact('medications'));
    }

    public function showMedication($id)
    {
        $medication = Medication::with('stock')->findOrFail($id);
        return view('user.pharmacy.medications.show', compact('medication'));
    }

    public function showPrescriptionRefillForm()
    {
        return view('user.pharmacy.prescription-refill.create');
    }

    public function storePrescriptionRefill(Request $request)
    {
        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'prescription_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|numeric|min:1',
        ]);

        $prescriptionRefill = new PrescriptionRefill([
            'user_id' => Auth::id(),
            'medication_id' => $validated['medication_id'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
        ]);

        if ($request->hasFile('prescription_image')) {
            $imagePath = $request->prescription_image->store('prescription-images', 'public');
            $prescriptionRefill->prescription_image = $imagePath;
        }

        $prescriptionRefill->save();

        return redirect()->route('user.pharmacy.prescription-refills.index')
            ->with('success', 'Prescription refill request submitted successfully!');
    }

    public function myPrescriptionRefills()
    {
        $refills = PrescriptionRefill::with(['medication', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('user.pharmacy.prescription-refills.index', compact('refills'));
    }

    public function showPrescriptionRefill($id)
    {
        $refill = PrescriptionRefill::with(['medication', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('user.pharmacy.prescription-refills.show', compact('refill'));
    }
}
