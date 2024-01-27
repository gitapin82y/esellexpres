<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $lastActivity = $user->last_activity;

            if ($lastActivity === null) {
                auth()->logout();
                return redirect('/login')->with('status', 'You have been automatically logged out.');
            }
    
            if ($lastActivity && now()->diffInDays($lastActivity) >= 2) {
                auth()->logout();
                return redirect('/login')->with('status', 'You have been automatically logged out due to no activity for 2 days.');
            }
    
            // Update waktu terakhir aktivitas
            $user->update(['last_activity' => now()]);
        }
    
        return $next($request);
    }
}
