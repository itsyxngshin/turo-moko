<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


class LandingNavbar extends Component
{
   public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirectRoute('auth.login'); 
    }

    public function render()
    {
        return view('livewire.landing-navbar');
    }
}
