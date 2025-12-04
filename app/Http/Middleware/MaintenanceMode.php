<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if Maintenance Mode is turned ON in the DB
        $isMaintenanceOn = Setting::where('key', 'maintenance_mode')->value('value') === '1';

        if ($isMaintenanceOn) {
            
            // 2. ALWAYS Allow access to Login and Logout routes
            // Otherwise admins can't log in to fix things!
            if ($request->routeIs('auth.login') || $request->routeIs('auth.logout')) {
                return $next($request);
            }

            // 3. Allow Logged-in Admins
            if (Auth::check() && Auth::user()->role->role_name === 'admin') {
                return $next($request);
            }

            // 4. Block everyone else
            // You can return a 503 error page, or a custom view
            abort(503, 'The system is currently undergoing maintenance. Please check back later.');
        }

        return $next($request);
    } 
}

