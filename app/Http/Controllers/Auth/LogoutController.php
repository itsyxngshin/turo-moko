<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log; // Don't forget to import this

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // [LOGGING]: Log the action BEFORE destroying the session
        // We check if a user is actually logged in first to avoid errors
        if (Auth::check()) {
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'auth.logout',
                'description' => 'User logged out.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Standard Logout Process
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
