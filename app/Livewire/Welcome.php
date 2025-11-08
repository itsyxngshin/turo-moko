<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.main')]
class Welcome extends Component
{
    public function mount(){
        // Only run this check if a user is logged in
        if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
            return redirect()->route('verification.notice');
        }

        // Check for the session flash
        if (session()->has('status')) {
            // Dispatch a browser event
            $this->dispatch('swal:alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => session('status'),
            ]);
        }
    }
    
    public function render(){
        return view('livewire.welcome');
    }
}
