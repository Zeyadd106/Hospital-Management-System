<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\VaccinationBooking;
use App\Models\HealthScreeningBooking;
use App\Models\AdminMessage;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for dashboard widgets
        $userCount = User::count();
        $doctorCount = Doctor::count();
        $clinicCount = Clinic::count();
        $departmentCount = Department::count();
        
        // Get total bookings count (both vaccination and health screening)
        $vaccinationBookingCount = VaccinationBooking::count();
        $healthScreeningBookingCount = HealthScreeningBooking::count();
        $totalBookingCount = $vaccinationBookingCount + $healthScreeningBookingCount;
        
        $messageCount = AdminMessage::where('status', 'pending')->count();
        
        // Get recent users
        $recentUsers = User::latest()->take(5)->get();
        
        // Get recent bookings (combining both types)
        $recentVaccinations = VaccinationBooking::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($booking) {
                $booking->booking_type = 'Vaccination';
                return $booking;
            });
            
        $recentScreenings = HealthScreeningBooking::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($booking) {
                $booking->booking_type = 'Health Screening';
                return $booking;
            });
            
        $recentBookings = $recentVaccinations->concat($recentScreenings)
            ->sortByDesc('created_at')
            ->take(5);
        
        // Get recent messages
        $recentMessages = AdminMessage::with('user', 'repliedBy')
            ->latest()
            ->take(5)
            ->get();
        
        // Get combined booking statistics by month
        $vaccinationStats = VaccinationBooking::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        $screeningStats = HealthScreeningBooking::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
        
        // Format for chart
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $chartLabels[] = $monthName;
            $chartData[] = ($vaccinationStats[$i] ?? 0) + ($screeningStats[$i] ?? 0);
        }
        
        return view('admin.dashboard', compact(
            'userCount', 
            'doctorCount', 
            'clinicCount',
            'departmentCount',
            'totalBookingCount', 
            'messageCount', 
            'recentUsers', 
            'recentBookings', 
            'recentMessages',
            'chartLabels',
            'chartData'
        ));
    }
}
