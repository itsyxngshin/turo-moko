<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class VerifyEmail extends Component
{
    public $code = ['', '', '', ''];

    public function verify()
    {
        $this->validate([
            'code' => 'required|array|size:4',
            'code.*' => 'required|numeric|digits:1',
        ]);

        $user = User::find(Auth::id());
        $user = Auth::user();

        $enteredCode = implode('', $this->code);
        if ($enteredCode === $user->verification_code) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            User::save();

            session()->flash('success', 'Your account has been verified successfully.');
            return redirect()->route('dashboard');
        }

        session()->flash('error', 'Invalid verification code.');
    }

    public function resend()
    {
        $user = Auth::user();
        $code = rand(1000, 9999);

        $user->verification_code = $code;
        User::save();

        Mail::raw("Your verification code is: {$code}", function ($message) use ($user) {
            $message->to($user->email)->subject('Verification Code');
        });

        session()->flash('success', 'Verification code resent.');
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->with(['layout' => 'layouts.layout2']);
    }
}
