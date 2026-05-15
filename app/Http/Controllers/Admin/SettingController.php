<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('admin.settings.index');
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}