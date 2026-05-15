<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $role = $request->user()->role;
            
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'doctor':
                    return redirect()->route('doctor.dashboard');
                case 'pharmacy':
                    return redirect()->route('pharmacy.dashboard');
                case 'user':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return $next($request);
    }
}