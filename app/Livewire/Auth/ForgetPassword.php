<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

#[Layout('layouts.password')]

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

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->message = __($status)
            : $this->addError('email', __($status));
    }

    public function render(){
        return view('livewire.auth.forget-password');
    }
}
