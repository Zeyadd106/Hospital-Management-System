<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use App\Models\MedicationManagementRequest;
use App\Models\PrescriptionRefill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Pharmacy;
use App\Models\Medicine;
use App\Models\Order;

class PharmacyController extends Controller
{
    /**
     * Display the pharmacy home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pharmacies = Pharmacy::where('status', true)
            ->with('medications')
            ->paginate(12);

        return view('pharmacy.index', compact('pharmacies'));
    }

    /**
     * Display the pharmacy page from the main navigation.
     *
     * @return \Illuminate\View\View
     */
    public function pharmacy()
    {
        return view('pages.pharmacy');
    }

    /**
     * Display the prescription refill page.
     *
     * @return \Illuminate\View\View
     */
    public function prescriptionRefill()
    {
        return view('pharmacy.prescription-refill');
    }

    /**
     * Store a new prescription refill request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePrescriptionRefill(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'prescription_number' => 'nullable|string|max:50',
            'medication_details' => 'required|string',
            'delivery_requested' => 'boolean',
            'delivery_address' => 'required_if:delivery_requested,1|nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $refill = PrescriptionRefill::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'prescription_number' => $request->prescription_number,
                'medication_details' => $request->medication_details,
                'delivery_requested' => $request->has('delivery_requested'),
                'delivery_address' => $request->delivery_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'confirmation_code' => strtoupper(Str::random(8))
            ]);

            return redirect()->route('pharmacy.prescription-refill.confirmation', $refill->id)
                           ->with('success', 'Prescription refill request submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing prescription refill: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'An error occurred while submitting your request: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the prescription refill confirmation page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function prescriptionRefillConfirmation($id)
    {
        try {
            $refill = PrescriptionRefill::findOrFail($id);
            return view('pharmacy.prescription-refill-confirmation', compact('refill'));
        } catch (\Exception $e) {
            Log::error('Error showing prescription refill confirmation: ' . $e->getMessage());
            return redirect()->route('pharmacy.index')
                           ->with('error', 'Request not found.');
        }
    }

    /**
     * Display the medication counseling page.
     *
     * @return \Illuminate\View\View
     */
    public function medicationCounseling()
    {
        return view('pharmacy.medication-counseling');
    }

    /**
     * Display the home delivery page.
     *
     * @return \Illuminate\View\View
     */
    public function homeDelivery()
    {
        return view('pharmacy.home-delivery');
    }

    /**
     * Display the medication management page.
     *
     * @return \Illuminate\View\View
     */
    public function medicationManagement()
    {
        return view('pharmacy.medication-management');
    }

    /**
     * Store a new medication management request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMedicationManagement(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'medications' => 'nullable|string',
            'services' => 'required|array|min:1',
            'special_requests' => 'nullable|string',
        ]);

        try {
            $management = MedicationManagementRequest::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'medications' => $request->medications,
                'services' => $request->services,
                'special_requests' => $request->special_requests,
                'status' => 'pending',
                'confirmation_code' => strtoupper(Str::random(8))
            ]);

            return redirect()->route('pharmacy.medication-management.confirmation', $management->id)
                           ->with('success', 'Medication management request submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing medication management: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'An error occurred while submitting your request: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the medication management confirmation page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function medicationManagementConfirmation($id)
    {
        try {
            $management = MedicationManagementRequest::findOrFail($id);
            return view('pharmacy.medication-management-confirmation', compact('management'));
        } catch (\Exception $e) {
            Log::error('Error showing medication management confirmation: ' . $e->getMessage());
            return redirect()->route('pharmacy.index')
                           ->with('error', 'Request not found.');
        }
    }

    /**
     * Display the user's medication management requests.
     *
     * @return \Illuminate\View\View
     */
    public function myMedicationManagement()
    {
        try {
            $managements = MedicationManagementRequest::where('user_id', Auth::id())
                                             ->orderBy('created_at', 'desc')
                                             ->get();
                                             
            return view('pharmacy.my-medication-management', compact('managements'));
        } catch (\Exception $e) {
            Log::error('Error fetching medication managements: ' . $e->getMessage());
            return view('pharmacy.my-medication-management', ['managements' => collect([])]);
        }
    }

    /**
     * Display the user's prescription refill requests.
     *
     * @return \Illuminate\View\View
     */
    public function myPrescriptionRefills()
    {
        try {
            $refills = PrescriptionRefill::where('user_id', Auth::id())
                                       ->orderBy('created_at', 'desc')
                                       ->get();
                                       
            return view('pharmacy.my-prescription-refills', compact('refills'));
        } catch (\Exception $e) {
            Log::error('Error fetching prescription refills: ' . $e->getMessage());
            return view('pharmacy.my-prescription-refills', ['refills' => collect([])]);
        }
    }

    /**
     * Verify a medication management request by confirmation code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyMedicationManagement(Request $request)
    {
        $request->validate([
            'confirmation_code' => 'required|string'
        ]);
        
        try {
            $management = MedicationManagementRequest::where('confirmation_code', $request->confirmation_code)->first();
            
            if (!$management) {
                return redirect()->back()->with('error', 'Invalid confirmation code.');
            }
            
            return redirect()->route('pharmacy.medication-management.show', $management->id);
        } catch (\Exception $e) {
            Log::error('Error verifying medication management: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'An error occurred while verifying your request.');
        }
    }

    /**
     * Display a specific medication management request.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showMedicationManagement($id)
    {
        try {
            $management = MedicationManagementRequest::findOrFail($id);
            
            // Check if the management belongs to the logged-in user
            if (Auth::check() && $management->user_id != Auth::id()) {
                return redirect()->route('pharmacy.my-medication-management')
                               ->with('error', 'Unauthorized access.');
            }
            
            return view('pharmacy.medication-management-show', compact('management'));
        } catch (\Exception $e) {
            Log::error('Error showing medication management details: ' . $e->getMessage());
            return redirect()->route('pharmacy.my-medication-management')
                           ->with('error', 'Request not found.');
        }
    }

    /**
     * Verify a prescription refill request by confirmation code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPrescriptionRefill(Request $request)
    {
        $request->validate([
            'confirmation_code' => 'required|string'
        ]);
        
        try {
            $refill = PrescriptionRefill::where('confirmation_code', $request->confirmation_code)->first();
            
            if (!$refill) {
                return redirect()->back()->with('error', 'Invalid confirmation code.');
            }
            
            return redirect()->route('pharmacy.prescription-refill.show', $refill->id);
        } catch (\Exception $e) {
            Log::error('Error verifying prescription refill: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'An error occurred while verifying your request.');
        }
    }

    /**
     * Display a specific prescription refill request.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showPrescriptionRefill($id)
    {
        try {
            $refill = PrescriptionRefill::findOrFail($id);
            
            // Check if the refill belongs to the logged-in user
            if (Auth::check() && $refill->user_id != Auth::id()) {
                return redirect()->route('pharmacy.my-prescription-refills')
                               ->with('error', 'Unauthorized access.');
            }
            
            return view('pharmacy.prescription-refill-show', compact('refill'));
        } catch (\Exception $e) {
            Log::error('Error showing prescription refill details: ' . $e->getMessage());
            return redirect()->route('pharmacy.my-prescription-refills')
                           ->with('error', 'Request not found.');
        }
    }

    /**
     * Display a specific pharmacy.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $medications = $pharmacy->medications()->paginate(10);

        return view('pharmacy.show', compact('pharmacy', 'medications'));
    }

    /**
     * Search for pharmacies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $pharmacies = Pharmacy::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('address', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->with('medications')
            ->paginate(12);

        return view('pharmacy.index', compact('pharmacies', 'query'));
    }

    /**
     * Search for medicines.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function searchMedicine(Request $request)
    {
        $query = $request->input('query');
        
        $medicines = Medicine::whereHas('pharmacy', function($q) {
            $q->where('status', true);
        })
        ->where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orWhereHas('pharmacy', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                });
        })
        ->with('pharmacy')
        ->paginate(12);

        return view('pharmacy.search-results', compact('medicines', 'query'));
    }

    public function indexMedicine()
    {
        $medicines = Medicine::where('status', true)
            ->orderBy('name')
            ->get();
        
        return view('pharmacy.index', compact('medicines'));
    }

    public function showMedicine(Medicine $medicine)
    {
        return view('pharmacy.show', compact('medicine'));
    }

    /**
     * Add a medicine to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMedicineToCart(Request $request, Medicine $medicine)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart['medicine_'.$medicine->id])) {
            $cart['medicine_'.$medicine->id]['quantity']++;
        } else {
            $cart['medicine_'.$medicine->id] = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'price' => $medicine->price,
                'quantity' => 1,
                'type' => 'medicine'
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Medicine added to cart successfully!');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('pharmacy.cart', compact('cart'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if(count($cart) == 0) {
            return redirect()->route('pharmacy.index')->with('error', 'Cart is empty');
        }
        
        return view('pharmacy.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $request->total_amount,
            'status' => 'pending'
        ]);
        
        // Clear cart after order is placed
        session()->forget('cart');
        
        return redirect()->route('pharmacy.index')
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Display all medications with cart functionality.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function medications(Request $request)
    {
        $query = Medication::with('stock')->where('is_active', true);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
        
        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        // Apply price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Apply in-stock filter
        if ($request->has('in_stock') && $request->in_stock) {
            $query->whereHas('stock', function($q) {
                $q->where('quantity', '>', 0);
            });
        }
        
        $medications = $query->orderBy('name')->paginate(12);
        
        // Get all unique categories for the filter dropdown
        $categories = Medication::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();
        
        return view('pharmacy.medications', compact('medications', 'categories'));
    }

    /**
     * Display a specific medication.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showMedication($id)
    {
        $medication = Medication::with('stock')->findOrFail($id);
        
        // Get related medications in the same category
        $relatedMedications = Medication::with('stock')
            ->where('is_active', true)
            ->where('id', '!=', $id)
            ->where('category', $medication->category)
            ->take(4)
            ->get();
        
        return view('pharmacy.medication-details', compact('medication', 'relatedMedications'));
    }

    /**
     * Add a medication to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMedicationToCart(Request $request)
    {
        $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'sometimes|integer|min:1|max:10',
        ]);
        
        $medicationId = $request->medication_id;
        $quantity = $request->quantity ?? 1;
        
        $medication = Medication::with('stock')->findOrFail($medicationId);
        
        // Check if medication is in stock
        if (!$medication->stock || $medication->stock->quantity < $quantity) {
            return redirect()->back()->with('error', 'This medication is out of stock or not enough quantity available.');
        }
        
        // Get the current cart from the session
        $cart = session()->get('cart', []);
        
        // If the medication is already in the cart, update the quantity
        if (isset($cart[$medicationId])) {
            $cart[$medicationId]['quantity'] += $quantity;
        } else {
            // Add the medication to the cart
            $cart[$medicationId] = [
                'id' => $medication->id,
                'name' => $medication->name,
                'price' => $medication->price,
                'quantity' => $quantity,
                'image' => $medication->image_path,
            ];
        }
        
        // Update the cart in the session
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Medication added to cart successfully!');
    }

    /**
     * View the cart.
     *
     * @return \Illuminate\View\View
     */
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('pharmacy.cart', compact('cart'));
    }

    /**
     * Update the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0|max:10',
        ]);
        
        $cart = session()->get('cart', []);
        
        foreach ($request->quantities as $id => $quantity) {
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('pharmacy.cart')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('pharmacy.cart')->with('success', 'Item removed from cart successfully!');
    }

    /**
     * Redirect to user medication details page
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToUserMedicationDetails($id)
    {
        return redirect()->route('user.pharmacy.medications.show', ['id' => $id]);
    }
}