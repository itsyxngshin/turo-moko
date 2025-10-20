<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Password;

class ForgetPassword extends Component
{
    #[Rule('required|email')]
    public $email = '';

    public $status = '';

    /**
     * Handle the form submission.
     */
    public function sendResetLink()
    {
        $this->validate();

        // Pass the email as an array, not a string
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __('passwords.sent');
            $this->email = ''; // Clear the input
        } 
        
        else {
            // The $status string is a translation key (e.g., 'passwords.user')
            $this->addError('email', __($status));
        }
    }
    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}
