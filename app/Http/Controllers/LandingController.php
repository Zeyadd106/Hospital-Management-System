<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Department;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get doctors without filtering by status
        $doctors = Doctor::take(4)->get();
        $services = Service::take(6)->get();
        $departments = Department::take(3)->get();
        
        return view('landing', compact('doctors', 'services', 'departments'));
    }
}
