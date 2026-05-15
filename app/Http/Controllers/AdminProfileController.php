<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Update the admin profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Change admin password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        Auth::user()->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Password updated successfully.');
    }
}
