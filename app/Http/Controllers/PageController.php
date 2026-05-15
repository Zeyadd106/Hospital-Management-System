<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class PageController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Display the departments page.
     *
     * @return \Illuminate\View\View
     */
    public function departments()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Display the blog page.
     *
     * @return \Illuminate\View\View
     */
    public function blog()
    {
        return view('blog.index');
    }
}
