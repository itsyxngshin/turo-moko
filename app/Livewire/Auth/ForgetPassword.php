<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordCodeMail;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;

#[Layout('layouts.password')]
#[Title('Forgot Password')]
class ForgetPassword extends Component
{
    // Step 1: Email Input
    #[Rule('required|email|exists:users,email')]
    public $email = '';

    // Step 2: Code & Password Input
    #[Rule('required|min:8|max:8')]
    public $code = '';
    
    #[Rule('required|min:8|confirmed')]
    public $password = '';
    
    public $password_confirmation = '';

    public $step = 1; // 1 = Email, 2 = Verify & Reset
    public $status = '';

    /**
     * Step 1: Generate Code and Send Email
     */
    public function sendResetCode()
    {
        $this->validate(['email' => 'required|email|exists:users,email']);

        // 1. Generate an 8-character random code (Uppercase + Numbers)
        $resetCode = Str::upper(Str::random(8));

        // 2. Store in the standard password_reset_tokens table
        // We delete old tokens for this email first
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $this->email,
            'token' => Hash::make($resetCode), // Hash it for security
            'created_at' => now()
        ]);

        // 3. Send the Email
        Mail::to($this->email)->send(new ResetPasswordCodeMail($resetCode));
        
        // 4. Advance Step
        $this->step = 2;
        $this->status = 'Code sent! Please check your email.';
        
        Log::create([
            'user_id' => null,
            'action' => 'auth.reset_code_requested',
            'description' => 'User requested a password reset code.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => ['email' => $this->email]
        ]);
        // Dispatch alert
        $this->dispatch('reset-link-sent', [
            'message' => 'We have emailed you an 8-character verification code.'
        ]);
    }

    /**
     * Step 2: Verify Code and Update Password
     */
    public function verifyAndReset()
    {
        $admins = User::whereHas('role', function ($query) {
                $query->where('role_name', 'admin');
            })->get();

        $this->validate([
            'code' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // FIX: Force input to Uppercase and remove spaces
        $upPin = Str::upper(trim($this->code));

        // 1. Retrieve the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        // 2. Check if record exists and code matches
        if (!$record || !Hash::check($upPin, $record->token)) {
            $this->addError('code', 'Invalid recovery code.');
            return;
        }

        // 3. Check for expiration (e.g., 60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            $this->addError('code', 'This code has expired.');
            return;
        }

        // 4. Update User Password
        $user = User::where('email', $this->email)->first();
        $user->forceFill([
            'password' => Hash::make($this->password)
        ])->save();

        // 5. Clean up token
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        Log::create([
        'user_id' => $user->id,
        'action' => 'auth.password_reset',
        'description' => 'User successfully reset their password via email code.',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'properties' => [
            'email' => $user->email,
            'reset_method' => '8_char_code', // Good to track if you change methods later
            'timestamp' => now()->toDateTimeString()
            ]
        ]);

        $identify = $user->role->role_name ?? 'learner';
        
                $match = match($identify) {
                    'implementer' => route('admin.settings'), 
                    'learner' => route('admin.settings'),
                    default => route('homepage'),
                };

                $notification = new \App\Notifications\GeneralNotification(
                    'Forget Password Successful', 
                    "The user {$user->username} has logged into the system", 
                    $match // Link admins to the list
                );
                Notification::send($admins, $notification);

        // 6. Redirect to login
        $this->dispatch('reset-link-sent', [
            'message' => 'Password reset successful! Redirecting...'
        ]);

        return redirect()->route('auth.login');
    }

    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}