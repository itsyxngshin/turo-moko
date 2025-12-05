<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;

#[Layout('layouts.auth')] 
class VerifyEmail extends Component
{
    // Updated to 6 empty strings for 6 inputs
    public $code = ['', '', '', '', '', ''];

    public function verify()
    {
        $this->validate([
            'code' => 'required|array|size:6', // Changed size to 6
            'code.*' => 'required|numeric|digits:1',
        ]);

        $user = User::find(Auth::id());
        $admins = User::whereHas('role', function ($query) {
            $query->where('role_name', 'admin');
        })->get();

        // Convert array to string
        $enteredCode = implode('', $this->code);

        if ($enteredCode === $user->verification_code) {
            
            // --- SUCCESS ---
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->save(); // FIXED: User::save() is static and incorrect here

            // Redirect Logic (Kept your existing logic)
            $roleName = $user->role->role_name ?? 'learner';
        
            $redirectUrl = match($roleName) {
                'admin' => route('admin.hub'),
                'implementer' => route('implementor.hub'), 
                'learner' => route('learner.hub'),
                default => route('homepage'),
            };

            $user->notify(new \App\Notifications\GeneralNotification(
                'Now Verified', 
                'You can now access your account!.', 
                $redirectUrl
            ));

            $match = match($roleName) {
                'implementer' => route('admin.implementors'), 
                'learner' => route('admin.enrollees'),
                default => route('homepage'),
            };

            $notification = new \App\Notifications\GeneralNotification(
                'Verification Successful', 
                "The user {$user->username} has been verified.", 
                route('admin.enrollees') // Link admins to the list
            );

            // 3. Send to all admins at once using the Facade
            Notification::send($admins, $notification);


            $this->dispatch('swal:alert', [
                'type' => 'success',
                'title' => 'Verification Successful!',
                'text' => 'Redirecting...',
                'timer' => 2000,
                'redirectUrl' => $redirectUrl
            ]);
        } 
        else {
            // --- FAILURE ---
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Invalid Code',
                'text' => 'The code entered is incorrect.',
                'timer' => 3000
            ]);
            
            // Optional: Reset inputs on fail
            $this->code = ['', '', '', '', '', '']; 
        }
    }

    public function resend()
    {
        $user = User::find(Auth::id());
        $key = 'resend-verification:' . $user->id;

        // 1. Check if they are spamming (Limit: 1 email per 60 seconds)
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Please Wait',
                'text' => "You can resend code again in $seconds seconds.",
                'timer' => 3000
            ]);
            return;
        }
        
        // Optional: Add Rate Limiting here if you want extra security
        // if (rate_limiter_check...) return;

        $code = rand(100000, 999999); 

        $user->verification_code = $code;
        $user->save();

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        // 1. Dispatch SweetAlert (Toast) for top-right notification
        $this->dispatch('swal:alert', [
            'type' => 'success',
            'title' => 'Code Sent',
            'text' => 'Please check your email inbox.',
            'timer' => 3000
        ]);

        // 2. Flash a session key for the inline UI prompt (The green box)
        session()->flash('resend_success', true);
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
