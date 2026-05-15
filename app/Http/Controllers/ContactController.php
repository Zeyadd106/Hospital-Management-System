<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Department;
use App\Models\AdminMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display the contact form with doctors and departments
     */
    public function index()
    {
        // Fetch active doctors grouped by department
        $departments = Department::with(['doctors' => function($query) {
            $query->where('status', 'active')->where('is_available', true);
        }])->get();

        return view('contact.index', compact('departments'));
    }

    /**
     * Store a contact message
     */
    public function store(Request $request)
    {
        $request->validate([
            'message_type' => 'required|in:admin_support,doctor_consultation',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        // Determine message destination
        if ($request->message_type === 'admin_support') {
            // Send to admin support
            $adminMessage = AdminMessage::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return redirect()->route('contact.index')
                ->with('success', 'Your support request has been sent. We will get back to you soon.');
        } else {
            // Send to specific doctor
            $message = Message::create([
                'user_id' => Auth::id(),
                'doctor_id' => $request->doctor_id,
                'content' => $request->message,
                'is_from_user' => true
            ]);

            return redirect()->route('user.messages.show', $request->doctor_id)
                ->with('success', 'Your message to the doctor has been sent.');
        }
    }

    /**
     * Get doctors for a specific department
     */
    public function getDoctorsByDepartment($departmentId)
    {
        $doctors = Doctor::where('department_id', $departmentId)
            ->where('status', 'active')
            ->where('is_available', true)
            ->get();

        return response()->json($doctors);
    }
}
