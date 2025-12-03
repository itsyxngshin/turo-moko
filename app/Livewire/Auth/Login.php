<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')] 
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            request()->session()->regenerate();

            $user = User::with('role', 'profile')->find(Auth::id());

            if (!$user || !$user->role) {
                $this->addError('email', 'No role assigned to user.');
                Auth::logout();
                return;
            }

            $redirect = match ($user->role->role_name) {
                'admin' => route('admin.hub'),
                'learner' => route('learner.hub'),
                'implementer' => route('implementer.hub'),
                default => route('homepage'),
            };

            $this->dispatch('swal:alert', [
                'type' => 'success', // matches 'icon' in your global script
                'title' => 'Welcome Back!',
                'text'  => 'Login successful. Redirecting...',
                'timer' => 2000,
                'redirectUrl' => $redirect // matches 'redirectUrl' in global script
            ]);

            return $this->redirect($redirect, navigate: true);
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}