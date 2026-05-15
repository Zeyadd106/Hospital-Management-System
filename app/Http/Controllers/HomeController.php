<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Show the departments page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function departments()
    {
        $departments = Department::all();
        return view('departments', compact('departments'));
    }
    
    /**
     * Show the blog page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function blog()
    {
        return view('blog');
    }
    
    public function dashboard()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'pharmacy':
                return redirect()->route('pharmacy.dashboard');
            case 'user':
                return view('dashboard', [
                    'recentActivity' => $user->getRecentActivity()
                ]);
            default:
                return redirect()->route('home');
        }
    }
}
