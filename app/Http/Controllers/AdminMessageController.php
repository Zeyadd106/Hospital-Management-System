<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('role:user')->only(['index', 'show']);
        $this->middleware('role:admin')->only(['adminIndex', 'adminShow', 'updateStatus']);
    }

    /**
     * Display a listing of the resource for admins.
     */
    public function adminIndex(Request $request)
    {
        // For admins, show all contact messages
        $messages = AdminMessage::latest()->paginate(10);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If user is an admin, show all messages
        if (Auth::user()->role === 'admin') {
            return $this->adminIndex($request);
        }

        // For non-admin users, show their own messages
        $messages = AdminMessage::where('user_id', Auth::id())
            ->orWhere('email', Auth::user()->email)
            ->latest()
            ->paginate(10);

        return view('contact.messages.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Check if the user is an admin or the message owner
        $message = AdminMessage::findOrFail($id);

        if (Auth::user()->role !== 'admin' && 
            $message->user_id !== Auth::id() && 
            $message->email !== Auth::user()->email) {
            abort(403, 'Unauthorized access');
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Alias for show method to maintain backward compatibility
     */
    public function adminShow($id)
    {
        // For admin view, bypass user check
        $message = AdminMessage::findOrFail($id);
        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }

    /**
     * Update message status (for admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $this->authorize('update', AdminMessage::class);

        $message = AdminMessage::findOrFail($id);
        $message->update([
            'status' => $request->status,
            'replied_at' => now(),
            'replied_by' => Auth::id()
        ]);

        return redirect()->route('admin.contact-messages.show', $message->id)
            ->with('success', 'Message status updated successfully.');
    }

    /**
     * Unsupported methods for this resource
     */
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit($id) { abort(404); }
    public function destroy($id) { abort(404); }
}
