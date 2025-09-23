<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Settings extends Component
{
    public $username;
    public $email;
    public $password;

    public function mount()
    {
        $user = Auth::user();

        // Check if user is logged in
        if (!$user) {
            abort(403, 'Unauthorized'); // or redirect()->route('login');
        }

        $this->username = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Empty by default
    }

    public function save()
    {
        $this->validate([
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $user->name = $this->username;
        $user->email = $this->email;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        session()->flash('message', 'Settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.learner.settings');
    }
}
