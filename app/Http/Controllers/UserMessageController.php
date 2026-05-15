<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    public function index()
    {
        try {
            // Get unique doctors who the user has messaged or received messages from
            $doctors = Message::where('user_id', Auth::id())
                ->select('doctor_id')
                ->whereNotNull('doctor_id')  // Make sure doctor_id is not null
                ->distinct()
                ->get()
                ->map(function($message) {
                    // Get the doctor with error handling
                    $doctor = Doctor::find($message->doctor_id);
                    return $doctor;
                })
                ->filter()  // Remove any null values
                ->values(); // Reset array keys
            
            // Check if user has any conversations
            if ($doctors->isEmpty()) {
                // No conversations yet, show list of available doctors
                $availableDoctors = Doctor::where('is_available', true)
                    ->orderBy('name')
                    ->get();
                    
                return view('user.messages.index', [
                    'doctors' => $doctors,
                    'availableDoctors' => $availableDoctors,
                    'hasConversations' => false
                ]);
            }
            
            return view('user.messages.index', [
                'doctors' => $doctors,
                'hasConversations' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in UserMessageController@index: ' . $e->getMessage());
            return view('user.messages.index', [
                'doctors' => collect([]),
                'availableDoctors' => Doctor::where('is_available', true)->get(),
                'hasConversations' => false,
                'error' => 'There was an error loading your conversations. Please try again.'
            ]);
        }
    }

    public function show($doctorId)
    {
        // Fetch messages between the user and the specific doctor
        $messages = Message::where(function($query) use ($doctorId) {
            $query->where('user_id', Auth::id())
                  ->where('doctor_id', $doctorId);
        })->orWhere(function($query) use ($doctorId) {
            $query->where('user_id', $doctorId)
                  ->where('doctor_id', Auth::id());
        })->orderBy('created_at', 'asc')
          ->get();

        // Mark all messages from this doctor as read
        Message::where('user_id', Auth::id())
            ->where('doctor_id', $doctorId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $doctor = Doctor::findOrFail($doctorId);

        return view('user.messages.show', compact('messages', 'doctor'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'content' => 'required|string|max:1000'
            ]);

            // Create the message
            $message = Message::create([
                'user_id' => Auth::id(),
                'doctor_id' => $request->doctor_id,
                'content' => $request->content,
                'is_from_user' => true,
                'read_at' => null
            ]);

            // Optionally, send notification to doctor (implementation details can be added)
            
            return redirect()->route('user.messages.show', $request->doctor_id)
                ->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to send message. Please try again.');
        }
    }

    public function listDoctors()
    {
        try {
            // Get all available doctors that users can message
            $doctors = Doctor::where('is_available', true)
                ->orderBy('name')
                ->get();
                
            return view('user.messages.list_doctors', compact('doctors'));
        } catch (\Exception $e) {
            \Log::error('Error listing doctors for messaging: ' . $e->getMessage());
            return redirect()->route('user.messages.index')
                ->with('error', 'Unable to load doctors list. Please try again.');
        }
    }
}
