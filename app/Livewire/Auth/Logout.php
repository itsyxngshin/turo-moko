<?php

namespace App\Livewire\Auth;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class Logout extends Component
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
        return view('livewire.auth.logout');
    }
}
