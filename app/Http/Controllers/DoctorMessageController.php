<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:doctor']);
    }

    /**
     * Display a listing of the doctor's conversations.
     */
    public function index()
    {
        try {
            // Get unique users who have messaged the doctor
            $users = Message::where('doctor_id', Auth::id())
                ->select('user_id')
                ->whereNotNull('user_id')
                ->distinct()
                ->get()
                ->map(function($message) {
                    $user = User::find($message->user_id);
                    // Get the last message for each user
                    $lastMessage = Message::where(function($query) use ($user) {
                        $query->where('user_id', $user->id)
                              ->where('doctor_id', Auth::id());
                    })->orWhere(function($query) use ($user) {
                        $query->where('user_id', Auth::id())
                              ->where('doctor_id', $user->id);
                    })->orderBy('created_at', 'desc')
                    ->first();
                    
                    if ($user) {
                        $user->last_message = $lastMessage;
                        $user->unread_count = Message::where('user_id', $user->id)
                            ->where('doctor_id', Auth::id())
                            ->whereNull('read_at')
                            ->where('is_from_user', true)
                            ->count();
                        return $user;
                    }
                    return null;
                })
                ->filter()
                ->sortByDesc(function($user) {
                    return $user->last_message ? $user->last_message->created_at : now()->subYears(100);
                })
                ->values();

            return view('doctor.messages.index', compact('users'));
        } catch (\Exception $e) {
            \Log::error('Error in DoctorMessageController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error loading your conversations.');
        }
    }

    /**
     * Display the conversation with a specific user.
     */
    public function conversation($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Mark all messages from this user as read
            Message::where('user_id', $userId)
                ->where('doctor_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            // Get the conversation messages
            $messages = Message::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('doctor_id', Auth::id());
            })->orWhere(function($query) use ($userId) {
                $query->where('user_id', Auth::id())
                      ->where('doctor_id', $userId);
            })->orderBy('created_at', 'asc')
            ->get();

            return view('doctor.messages.conversation', compact('messages', 'user'));
        } catch (\Exception $e) {
            \Log::error('Error in DoctorMessageController@conversation: ' . $e->getMessage());
            return redirect()->route('doctor.messages.index')
                ->with('error', 'There was an error loading the conversation.');
        }
    }

    /**
     * Send a message to a user.
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string|max:1000'
            ]);

            // Create the message
            $message = Message::create([
                'user_id' => $request->user_id,
                'doctor_id' => Auth::id(),
                'content' => $request->content,
                'is_from_user' => false,
                'read_at' => null
            ]);

            // You can add real-time notification here (e.g., using Pusher, WebSockets, etc.)
            // event(new NewMessage($message));


            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $message->load('doctor')
                ]);
            }

            return redirect()->back()->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            \Log::error('Error in DoctorMessageController@sendMessage: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'There was an error sending your message.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'There was an error sending your message. Please try again.');
        }
    }

    /**
     * Get unread message count for the doctor.
     */
    public function getUnreadCount()
    {
        try {
            $count = Message::where('doctor_id', Auth::id())
                ->whereNull('read_at')
                ->where('is_from_user', true)
                ->count();

            return response()->json([
                'status' => 'success',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in DoctorMessageController@getUnreadCount: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get unread message count.'
            ], 500);
        }
    }
}
