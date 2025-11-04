<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')]
class Login extends Component
{
    public $email;
    public $password = '';
    public $remember = false;
    public $showError = false;
    public function login(){
    $this->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    try {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                request()->session()->regenerate();

                $user = User::with('role')->find(Auth::id());

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

                $this->dispatchBrowserEvent('swal:success', [
                    'title' => 'Welcome, ' . $user->profile->first_name . '!',
                    'text' => 'Registration complete. Redirecting to your dashboard...',
                    'icon' => 'success',
                    'redirect' => $redirect
                ]);

                session()->flash('success', 'Login successful!');
            }

            else { 
                $this->addError('email', 'The provided credentials do not match our records.');
                $this->showError = true;
            }
        }

    catch (\Exception $e) {
        $this->addError('password', 'Something went wrong. Please try again.');
        $this->showError = true;
        return;
        }    
    }

    public function render()
    {
        return view('livewire.auth.login')->with(['layout' => 'layouts.layout2']);
    }
}
