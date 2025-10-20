<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Welcome extends Component
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
        return view('livewire.welcome')->layout('layouts.main');
    }
}
