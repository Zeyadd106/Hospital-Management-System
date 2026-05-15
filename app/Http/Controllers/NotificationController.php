<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Config;
use Pusher\PushNotifications\PushNotifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Display the specified notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Mark notification as read
        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }
        
        return view('notifications.show', compact('notification'));
    }

    /**
     * Mark a notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->is_read = true;
        $notification->save();
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->delete();
        
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }

    public function registerDevice(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'interests' => 'required|array',
        ]);

        $device = Device::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'keys' => $request->keys,
                'user_id' => auth()->id(),
                'interests' => json_encode($request->interests),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'interests' => 'required|array',
        ]);

        $beamsClient = new PushNotifications([
            'instanceId' => config('pusher.beams_instance_id'),
            'secretKey' => config('pusher.beams_secret_key'),
        ]);

        $publishResponse = $beamsClient->publishToInterests(
            $request->interests,
            [
                'web' => [
                    'notification' => [
                        'title' => $request->title,
                        'body' => $request->body,
                    ],
                ],
            ]
        );

        // Create database notification
        $notification = new Notification([
            'data' => [
                'title' => $request->title,
                'message' => $request->body,
            ],
            'type' => 'App\Notifications\GeneralNotification'
        ]);

        auth()->user()->notify($notification);

        return response()->json(['status' => 'success']);
    }

    public function sendReminder($type, $id)
    {
        $user = auth()->user();
        
        $message = '';
        $title = '';
        
        switch($type) {
            case 'vaccination':
                $vaccination = \App\Models\VaccinationBooking::findOrFail($id);
                $title = 'Vaccination Reminder';
                $message = "You have a vaccination appointment scheduled for " . $vaccination->date . " at " . $vaccination->time;
                break;
            
            case 'health-screening':
                $screening = \App\Models\HealthScreeningBooking::findOrFail($id);
                $title = 'Health Screening Reminder';
                $message = "You have a health screening appointment scheduled for " . $screening->date . " at " . $screening->time;
                break;
        }

        $notification = new Notification([
            'data' => [
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'id' => $id
            ],
            'type' => 'App\Notifications\AppointmentReminder'
        ]);

        $user->notify($notification);

        return response()->json(['status' => 'success']);
    }
}
