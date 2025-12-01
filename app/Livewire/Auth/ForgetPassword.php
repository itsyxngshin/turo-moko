<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.password')]
#[Title('Forgot Password')]

class ForgetPassword extends Component
{
    #[Rule('required|email')]
    public $email = '';
    public $message;
    public $status = '';

    /**
     * Handle the form submission.
     */
    public function sendResetLink(){
       $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            // Clear the email field
            $this->email = '';

            // Dispatch the event to the frontend with the success message
            $this->dispatch('reset-link-sent', [
                'message' => __($status)
            ]);

            return;
        }

        $this->addError('email', __($status));
    }

    public function render(){
        return view('livewire.auth.forget-password');
    }
}
