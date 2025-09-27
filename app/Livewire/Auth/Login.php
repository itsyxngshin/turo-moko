<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = User::where('id', Auth::id())->with('role')->first(); // Eager-load 'role'

            if (!$user->role) {
                return back()->with('error', 'No role assigned to user.');
            }
        
            $redirect = match($user->role->role_name) {
                'admin' => route('admin.hub'),
                'learner' => route('learner.hub'),
                'implementer' => route('implementer.hub'),
                default => route('homepage'),
            };
            return redirect($redirect)->with('success', 'Login successful!');
        }

        $this->addError('email', 'Invalid email or password.');
        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.main');;
    }
}
