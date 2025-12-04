<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Log;
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

        // Attempt Login
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            request()->session()->regenerate();

            $user = User::with('role', 'profile')->find(Auth::id());

            // CHECK: User has no role
            if (!$user || !$user->role) {
                
                // [LOGGING]: Log the error
                Log::create([
                    'user_id' => $user->id,
                    'action' => 'auth.login_failed',
                    'description' => 'User logged in but has no role assigned.',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $this->addError('email', 'No role assigned to user.');
                Auth::logout();
                return;
            }

            // [LOGGING]: SUCCESSFUL LOGIN
            Log::create([
                'user_id' => $user->id,
                'action' => 'auth.login',
                'description' => 'User logged in successfully.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => [
                    'role' => $user->role->role_name,
                    'email' => $user->email // Helpful for quick debugging
                ]
            ]);

            $redirect = match ($user->role->role_name) {
                'admin' => route('admin.hub'),
                'learner' => route('learner.hub'),
                'implementor' => route('implementor.hub'),
                default => route('homepage'),
            };

            $this->dispatch('swal:alert', [
                'type' => 'success',
                'title' => 'Welcome Back!',
                'text'  => 'Login successful. Redirecting...',
                'timer' => 2000,
                'redirectUrl' => $redirect
            ]);

            return $this->redirect($redirect, navigate: true);
        }

        // [LOGGING]: FAILED LOGIN (Wrong Password/Email)
        // We use null for user_id because we don't know who they are (or they don't exist)
        Log::create([
            'user_id' => null, 
            'action' => 'auth.login_attempt_failed',
            'description' => 'Failed login attempt (Invalid credentials).',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => [
                'attempted_email' => $this->email, // Capture what email they tried
                // NEVER log the password!
            ]
        ]);

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}