<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


class LandingNavbar extends Component
{
   public function logout()
    {
        // This helper handles session invalidation and token regeneration
        Auth::logout(); 

        // Redirect to the login page
        return $this->redirect(route('login'));  
    }

    public function render()
    {
        return view('livewire.landing-navbar');
    }
}
