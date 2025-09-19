<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VerifyPhone extends Component
{
    public $code = ['', '', '', ''];

    public function updatedCode()
    {
        // Auto move focus or validate input length
        $this->code = array_map(fn($digit) => substr($digit, 0, 1), $this->code);
    }

    public function verify()
    {
        $enteredCode = implode('', $this->code);

        // Example logic: Replace with real verification
        if ($enteredCode === "1234") {
            session()->flash('success', 'Phone number verified!');
        } else {
            session()->flash('error', 'Invalid code. Try again.');
        }
    }

    public function resend()
    {
        // Logic for resending SMS
        session()->flash('success', 'A new code has been sent.');
    }

    public function render()
    {
        return view('livewire.verify-phone');
    }
}
