<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the contact messages.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $messages = ContactMessage::with('doctor')->latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display the specified contact message.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $message = ContactMessage::with('doctor')->findOrFail($id);
        
        // Mark as read if it's unread
        if ($message->status === 'unread') {
            $message->status = 'read';
            $message->save();
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Show the form for replying to the specified contact message.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function reply($id)
    {
        $message = ContactMessage::with('doctor')->findOrFail($id);
        return view('admin.messages.reply', compact('message'));
    }

    /**
     * Send a reply to the specified contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendReply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $request->validate([
            'reply_subject' => 'required|string|max:255',
            'reply_message' => 'required|string',
        ]);

        // Update the message with reply information
        $message->reply = $request->reply_message;
        $message->reply_subject = $request->reply_subject;
        $message->status = 'replied';
        $message->replied_at = now();
        $message->save();

        // Send email reply
        Mail::to($message->email)->send(new ContactReply($message));

        return redirect()->route('admin.messages.show', $message->id)
            ->with('success', 'Reply sent successfully.');
    }

    /**
     * Archive the specified contact message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->status = 'archived';
        $message->save();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message archived successfully.');
    }

    /**
     * Remove the specified contact message from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}