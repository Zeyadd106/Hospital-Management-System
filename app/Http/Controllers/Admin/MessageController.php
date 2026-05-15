<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\MessageThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class MessageController extends Controller
{
    /**
     * Display a listing of the messages.
     *
     * @return View|Factory
     */
    public function index()
    {
        $messages = AdminMessage::with('user')
            ->latest()
            ->paginate(10);
        
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display the specified message.
     *
     * @param  int  $id
     * @return View|Factory
     */
    public function show($id)
    {
        $message = AdminMessage::with('user')
            ->findOrFail($id);
        
        // Mark as read if unread
        if ($message->status === 'pending') {
            // Use the update method with a valid enum value
            AdminMessage::where('id', $id)
                ->update(['status' => 'in_progress']);
            
            // Refresh the message to get the updated status
            $message = AdminMessage::with('user')->findOrFail($id);
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Show the form for replying to the specified message.
     *
     * @param  int  $id
     * @return View|Factory
     */
    public function reply($id)
    {
        $message = AdminMessage::findOrFail($id);
        
        return view('admin.messages.reply', compact('message'));
    }

    /**
     * Send a reply to the specified message.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $message = AdminMessage::findOrFail($id);
        $message->reply = $request->reply;
        $message->replied_at = now();
        $message->replied_by = Auth::id();
        $message->status = 'replied';
        $message->save();

        return redirect()->route('admin.messages.show', $message->id)
            ->with('success', 'Reply sent successfully.');
    }

    /**
     * Delete the specified message.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $message = AdminMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}