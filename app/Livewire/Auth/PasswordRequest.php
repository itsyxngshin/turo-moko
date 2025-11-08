<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.main')]

class PasswordRequest extends Component
{
    public function render()
    {
        return view('livewire.auth.password-request');
    }
}
