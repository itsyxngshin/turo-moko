<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public $username;
    public $email;
    public $password;
    public $profile_image;
    public $newProfileImage;

    public function mount()
    {
        $user = auth()->user() ?? (object) [
            'username' => 'DemoUser',
            'email' => 'demo@example.com',
            'profile_image' => 'images/default-profile.png',
        ];

        $this->username = $user->username;
        $this->email = $user->email;
        $this->profile_image = $user->profile_image;
    }

    public function save()
    {
        // Temporary mock save (replace with DB logic later)
        $this->dispatch('profile-updated');
        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.learner.edit-profile')
            ->layout('layouts.layout');
    }
}
