<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class ProfileModal extends Component
{
   public $showProfileModal = false; // MUST be public

    // Demo user data
    public $username = 'demouser';
    public $email = 'student@example.com';
    public $phone = '09213456776';
    public $bio = 'No bio available.';
    public $profile_image;

    public function render()
    {
        return view('livewire.learner.profile-modal');
    }
}