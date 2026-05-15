<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medication;
use App\Models\PrescriptionRefill;
use App\Models\MedicationManagementRequest;
use App\Models\StockItem;
use App\Models\Sale;

class PharmacyDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pharmacy_admin');
    }

    public function index()
    {
        $user = auth()->user();
        $pharmacy = $user->pharmacy;

        // Get statistics
        $totalMedications = Medication::where('pharmacy_id', $pharmacy->id)->count();
        $totalRefills = PrescriptionRefill::where('pharmacy_id', $pharmacy->id)->count();
        $pendingRefills = PrescriptionRefill::where('pharmacy_id', $pharmacy->id)
            ->where('status', 'pending')
            ->count();
        $totalRequests = MedicationManagementRequest::where('pharmacy_id', $pharmacy->id)->count();
        $totalStock = StockItem::count();
        $lowStock = StockItem::where('current_stock', '<', 'min_stock')->count();
        
        // Get today's sales
        $todaySales = Sale::whereDate('created_at', today())->sum('amount');
        
        // Get recent activities
        $recentActivities = Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get low stock items
        $lowStockItems = StockItem::where('current_stock', '<', 'min_stock')
            ->with('medication')
            ->get();

        return view('pharmacy.dashboard', compact(
            'pharmacy',
            'totalMedications',
            'totalRefills',
            'pendingRefills',
            'totalRequests',
            'totalStock',
            'lowStock',
            'todaySales',
            'recentActivities',
            'lowStockItems'
        ));
    }

    public function myPrescriptionRefills()
    {
        $user = auth()->user();
        $pharmacy = $user->pharmacy;
        
        $refills = PrescriptionRefill::where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pharmacy.prescription-refills.index', compact('refills'));
    }

    public function approvePrescriptionRefill($id)
    {
        $refill = PrescriptionRefill::findOrFail($id);
        
        if ($refill->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        $refill->status = 'approved';
        $refill->save();

        return redirect()->back()->with('success', 'Prescription refill approved successfully!');
    }

    public function rejectPrescriptionRefill($id)
    {
        $refill = PrescriptionRefill::findOrFail($id);
        
        if ($refill->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        $refill->status = 'rejected';
        $refill->save();

        return redirect()->back()->with('success', 'Prescription refill rejected successfully!');
    }

    public function processPrescriptionRefill($id)
    {
        $refill = PrescriptionRefill::findOrFail($id);
        
        if ($refill->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        $refill->status = 'processed';
        $refill->processed_at = now();
        $refill->save();

        return redirect()->back()->with('success', 'Prescription refill processed successfully!');
    }

    public function medicationManagement()
    {
        $user = auth()->user();
        $pharmacy = $user->pharmacy;
        
        $requests = MedicationManagementRequest::where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pharmacy.medication-management.index', compact('requests'));
    }
}
