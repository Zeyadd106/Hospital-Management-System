<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display the specified department.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $department = Department::findOrFail($id);
            $doctors = Doctor::where('department_id', $id)
                      ->orWhere('department', $department->name)
                      ->where('status', 'active')
                      ->get();
                      
            return view('departments.show', compact('department', 'doctors'));
        } catch (\Exception $e) {
            Log::error('Error in DepartmentController@show: ' . $e->getMessage());
            return redirect()->route('departments')
                ->with('error', 'Department not found or an error occurred.');
        }
    }
}
