<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if(Auth::check())
        {
            $expires_after  = Carbon::now()->addSeconds(5);
            if($user->role == "admin"){
                Cache::put('user-online' . Auth::user()->admin_id, true, $expires_after);
            } elseif($user->role == "manager"){
                Cache::put('user-online' . Auth::user()->manager_id, true, $expires_after);
            }
        }

        if (Auth::check() && Auth::user()->status == 0) {
            // User is logged in but their status is 0 (banned)
            Auth::logout(); // Logout the user
            return redirect()->back()->with('alert', 'Sorry dude! You are banned by our Admin 🚫');
        }

        return $next($request);

    }
}
