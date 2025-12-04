<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; 

class LandingNavbar extends Component
{
    // 1. Computed Property for the Dashboard Link
    #[Computed]
    public function dashboardUrl()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return route('homepage'); 
        }

        return match($user->role->role_name) {
            'admin'       => route('admin.hub'),
            'implementor' => route('implementer.hub'), 
            'learner'     => route('learner.hub'),
            default       => route('homepage'),
        };
    }

    // 2. Computed Property for the Profile Link
    #[Computed]
    public function profileUrl()
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return route('homepage'); 
        }

        return match($user->role->role_name) {
            'admin'       => route('homepage'), // Admins often don't have a public profile page
            'implementor' => route('implementer.profile'), 
            'learner'     => route('learner.profile'),
            default       => route('homepage'),
        };
    }

    // Note: The logout() function is not needed if you are using the <form> action in the view.
    // However, keeping it doesn't hurt if you switch to wire:click later.
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