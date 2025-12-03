<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; // Import this

class LandingNavbar extends Component
{
    // 1. Create a Computed Property for the Dashboard Link
    #[Computed]
    public function dashboardUrl()
    {
        $user = Auth::user();

        // Safety check: if no user or no role, go to home
        if (!$user || !$user->role) {
            return route('homepage'); 
        }

        // Match the role name to the specific route
        return match($user->role->role_name) {
            'admin'       => route('admin.hub'),
            'implementer' => route('implementer.hub'), // Ensure these routes exist in web.php
            'learner'     => route('learner.hub'),
            default       => route('homepage'),
        };
    }

    // 2. Create a Computed Property for the Profile Link (Optional)
    // If profiles are also different per role, use this. 
    // If everyone uses the same profile page, you don't need this function.
    #[Computed]
    public function profileUrl()
    {
        $user = Auth::user();

        // Safety check: if no user or no role, go to home
        if (!$user || !$user->role) {
            return route('homepage'); 
        }

        // Match the role name to the specific route
        return match($user->role->role_name) {
            'admin'       => route('admin.profile'),
            'implementer' => route('implementer.profile'), // Ensure these routes exist in web.php
            'learner'     => route('learner.profile'),
            default       => route('homepage'),
        };
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return $this->redirect(route('auth.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.landing-navbar');
    }
}