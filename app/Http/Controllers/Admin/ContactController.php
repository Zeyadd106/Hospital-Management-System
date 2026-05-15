<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctors = Doctor::all();
        $departments = Department::all();
        
        return view('contact.index', compact('doctors', 'departments'));
    }
    
    /**
     * Store a new contact message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // If doctor_id is not provided, set it to null (optional field)
        $doctorId = $request->doctor_id;

        // Alternatively, fetch a default doctor_id dynamically if needed
        if (!$doctorId) {
            $defaultDoctor = Doctor::first();
            $doctorId = $defaultDoctor ? $defaultDoctor->id : null;
        }

        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'doctor_id' => $doctorId, // Use null if no doctor is available
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending'
        ]);
        
        // You can add email notification here if needed
        
        return redirect()->route('contact.success')->with('success', 'Your message has been sent successfully!');
    }
    
    /**
     * Display success page after message submission
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('contact.success');
    }
    
    /**
     * Get doctors by department
     *
     * @param  int  $departmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsByDepartment($departmentId)
    {
        $doctors = Doctor::where('department_id', $departmentId)->get(['id', 'name', 'specialization']);
        
        return response()->json($doctors);
    }
}