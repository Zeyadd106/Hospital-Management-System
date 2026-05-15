<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->status = $request->status;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = $imagePath;
        }
        
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->status = $request->status;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image = $imagePath;
        }
        
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Delete service image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}