<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Require authentication for all methods
    }

    public function index()
    {
        // Get available doctors
        $doctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile', function($query) {
                $query->where('is_available', true);
            })
            ->with(['doctorProfile'])
            ->get();

        return view('chat.index', compact('doctors'));
    }

    public function chatWithDoctor($doctorId)
    {
        $doctor = User::where('id', $doctorId)
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', function($query) {
                $query->where('is_available', true);
            })
            ->with(['doctorProfile'])
            ->firstOrFail();

        $messages = Message::where(function($query) use ($doctorId) {
            $query->where('user_id', Auth::id())
                 ->where('doctor_id', $doctorId);
        })->orWhere(function($query) use ($doctorId) {
            $query->where('user_id', $doctorId)
                 ->where('doctor_id', Auth::id());
        })->orderBy('created_at')
           ->get()
           ->map(function($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'created_at' => $message->created_at,
                'is_from_user' => $message->user_id == Auth::id(),
                'read_at' => $message->read_at
            ];
        });

        return view('chat.chat', compact('doctor', 'messages'));
    }

    public function sendMessage(Request $request, $doctorId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'doctor_id' => $doctorId,
            'content' => $request->message,
            'read_at' => null
        ]);

        // Broadcast the message to the doctor
        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }

    public function getDoctorProfile($doctorId)
    {
        $doctor = User::where('id', $doctorId)
            ->with(['doctorProfile'])
            ->first();

        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        return response()->json($doctor);
    }
}