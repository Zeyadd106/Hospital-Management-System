<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClinicController extends Controller
{
    /**
     * Display a listing of the clinics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all active clinics with their departments
        $clinics = Clinic::where('status', 'active')
                        ->with('department')
                        ->get();
        
        // If no clinics exist, seed some sample data
        if ($clinics->isEmpty()) {
            $this->seedSampleClinics();
            $clinics = Clinic::where('status', 'active')
                            ->with('department')
                            ->get();
        }
        
        $departments = Department::where('status', 'active')->get();
        $doctors = Doctor::all(); // Add this line to fix the undefined variable error
        
        return view('clinics.index', compact('clinics', 'departments', 'doctors'));
    }

    /**
     * Display the specified clinic.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $clinic = Clinic::with('department')->findOrFail($id);
        
        // Get doctors associated with this clinic's department
        $doctors = Doctor::where('department_id', $clinic->department_id)
                        ->orWhere('department', $clinic->department->name ?? '')
                        ->get();
        
        return view('clinics.show', compact('clinic', 'doctors'));
    }
    
    /**
     * Seed sample clinics if none exist
     */
    private function seedSampleClinics()
    {
        // Get or create departments
        $departments = Department::where('status', 'active')->get();
        
        if ($departments->isEmpty()) {
            // Create sample departments if none exist
            $departments = [
                ['name' => 'Cardiology', 'status' => 'active'],
                ['name' => 'Dermatology', 'status' => 'active'],
                ['name' => 'Pediatrics', 'status' => 'active']
            ];
            
            foreach ($departments as $dept) {
                Department::create($dept);
            }
            
            $departments = Department::where('status', 'active')->get();
        }
        
        // Create sample clinics
        $clinics = [
            [
                'name' => 'Heart Health Clinic',
                'department_id' => 1,
                'status' => 'active',
                'description' => 'Specialized cardiac care and treatment',
                'location' => 'Main Building, 2nd Floor',
                'phone' => '+1234567890'
            ],
            [
                'name' => 'Skin Care Center',
                'department_id' => 2,
                'status' => 'active',
                'description' => 'Comprehensive dermatological services',
                'location' => 'Main Building, 3rd Floor',
                'phone' => '+1234567891'
            ],
            [
                'name' => 'Pediatric Care Center',
                'department_id' => 3,
                'status' => 'active',
                'description' => 'Specialized care for children',
                'location' => 'Main Building, 4th Floor',
                'phone' => '+1234567892'
            ]
        ];
        
        foreach ($clinics as $clinic) {
            Clinic::create($clinic);
        }
    }
}
