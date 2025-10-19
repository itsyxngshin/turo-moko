<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LandingNavbar extends Component
{
    protected $listeners = ['logoutConfirmed' => 'logout'];
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirectRoute('auth.login'); // change to your homepage route
    }

    public function render()
    {
        return view('livewire.landing-navbar');
    }
}
