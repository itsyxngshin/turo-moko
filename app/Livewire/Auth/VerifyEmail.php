<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

#[Layout('layouts.auth')] 
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
            
            // --- SUCCESS ---
            $user->email_verified_at = now();
            $user->verification_code = null;
            User::save();

            $roleName = $user->role->role_name; // e.g., 'admin', 'implementer'
            $redirectUrl = '';

            if ($roleName === 'admin') {
                $redirectUrl = route('admin.hub');
            } 
            elseif ($roleName === 'implementer') {
                $redirectUrl = route('implementer.hub');
            } 
            elseif ($roleName === 'learner') {
                $redirectUrl = route('learner.hub');
            } 
            else {
                $redirectUrl = route('homepage');
            }

            // 2. Dispatch one alert that will redirect on close
            $this->dispatch('swal:alert', [
                'type' => 'success',
                'title' => 'Verification Successful!',
                'text' => 'Your account is verified. You will now be redirected.',
                'timer' => 5000,
                'redirectUrl' => $redirectUrl // Send the URL to the listener
            ]);
        } 
        
        else {
            
            // --- FAILURE ---
            // Dispatch a failure alert
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Invalid Code',
                'text' => 'The verification code you entered is incorrect.',
                'timer' => 3000
            ]);
        }
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
        return view('livewire.auth.verify-email');
    }
}
