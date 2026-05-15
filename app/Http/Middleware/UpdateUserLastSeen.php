<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserLastSeen
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
        if (Auth::check()) {
            // Update last seen timestamp if more than 1 minute has passed since last update
            $user = Auth::user();
            if (!$user->last_seen || $user->last_seen->diffInMinutes(now()) > 1) {
                $user->last_seen = now();
                $user->timestamps = false; // Prevent updated_at from changing
                $user->save();
            }
        }

        return $next($request);
    }
}
