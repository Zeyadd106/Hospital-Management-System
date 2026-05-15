<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Pharmacy;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PharmacyMedicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pharmacy_admin');
    }

    /**
     * Display a listing of the medications.
     */
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;
        $medications = $pharmacy->medications()
            ->with('stock')
            ->latest()
            ->paginate(10);
            
        return view('pharmacy.medications.index', compact('medications'));
    }

    /**
     * Show the form for creating a new medication.
     */
    public function create()
    {
        return view('pharmacy.medications.create');
    }

    /**
     * Store a newly created medication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'required|string|max:255',
            'strength' => 'required|string|max:100',
            'form' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
            'batch_number' => 'required|string|max:100',
            'min_stock' => 'required|integer|min:1',
            'current_stock' => 'required|integer|min:0',
        ]);

        $pharmacy = Auth::user()->pharmacy;

        // Create the medication
        $medication = $pharmacy->medications()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'manufacturer' => $validated['manufacturer'],
            'strength' => $validated['strength'],
            'form' => $validated['form'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'expiry_date' => $validated['expiry_date'],
            'batch_number' => $validated['batch_number'],
            'status' => true,
        ]);

        // Create initial stock item
        $medication->stockItems()->create([
            'pharmacy_id' => $pharmacy->id,
            'batch_number' => $validated['batch_number'],
            'manufacturer' => $validated['manufacturer'],
            'expiry_date' => $validated['expiry_date'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'min_stock' => $validated['min_stock'],
            'current_stock' => $validated['current_stock'],
        ]);

        return redirect()->route('pharmacy.medications.index')
            ->with('success', 'Medication added successfully.');
    }

    /**
     * Display the specified medication.
     *
     * @param  \App\Models\Medication  $medication
     * @return \Illuminate\View\View
     */
    public function show(Medication $medication)
    {
        $this->authorize('view', $medication);
        
        // Eager load the stock relationship for the current pharmacy
        $pharmacyId = auth()->user()->pharmacy_id;
        
        $medication->load(['stockItems' => function($query) use ($pharmacyId) {
            $query->where('pharmacy_id', $pharmacyId);
        }]);
        
        // For convenience, add a 'stock' property to the medication
        $medication->stock = $medication->stockItems->first();
        
        // Calculate stock status
        if ($medication->stock) {
            $stockLevel = $medication->stock->current_stock;
            $minStock = $medication->stock->min_stock;
            
            if ($stockLevel <= 0) {
                $stockStatus = 'out_of_stock';
                $stockStatusClass = 'danger';
            } elseif ($stockLevel <= $minStock) {
                $stockStatus = 'low_stock';
                $stockStatusClass = 'warning';
            } else {
                $stockStatus = 'in_stock';
                $stockStatusClass = 'success';
            }
            
            $medication->stock->status = $stockStatus;
            $medication->stock->status_class = $stockStatusClass;
        }
        
        return view('pharmacy.medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified medication.
     */
    public function edit(Medication $medication)
    {
        $this->authorize('update', $medication);
        $stockItem = $medication->stockItems()->first();
        return view('pharmacy.medications.edit', compact('medication', 'stockItem'));
    }

    /**
     * Update the specified medication in storage.
     */
    public function update(Request $request, Medication $medication)
    {
        $this->authorize('update', $medication);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'required|string|max:255',
            'strength' => 'required|string|max:100',
            'form' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
            'batch_number' => 'required|string|max:100',
            'min_stock' => 'required|integer|min:1',
            'current_stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        // Update the medication
        $medication->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'manufacturer' => $validated['manufacturer'],
            'strength' => $validated['strength'],
            'form' => $validated['form'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'expiry_date' => $validated['expiry_date'],
            'batch_number' => $validated['batch_number'],
            'status' => $validated['status'],
        ]);

        // Update or create stock item
        $stockItem = $medication->stockItems()->firstOrNew();
        $stockItem->fill([
            'pharmacy_id' => $medication->pharmacy_id,
            'batch_number' => $validated['batch_number'],
            'manufacturer' => $validated['manufacturer'],
            'expiry_date' => $validated['expiry_date'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'min_stock' => $validated['min_stock'],
            'current_stock' => $validated['current_stock'],
        ]);
        $stockItem->save();

        return redirect()->route('pharmacy.medications.index')
            ->with('success', 'Medication updated successfully.');
    }

    /**
     * Remove the specified medication from storage.
     */
    public function destroy(Medication $medication)
    {
        $this->authorize('delete', $medication);
        
        // Check if medication has any associated records
        if ($medication->prescriptionRefills()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete medication with associated prescription refills.');
        }

        $medication->stockItems()->delete();
        $medication->delete();

        return redirect()->route('pharmacy.medications.index')
            ->with('success', 'Medication deleted successfully.');
    }
}
