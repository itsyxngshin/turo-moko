<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

#[Layout('layouts.main')]
class Reset extends Component
{
    public $token;

    #[Rule('required|email')]
    public $email;

    #[Rule('required|min:8|same:password_confirmation')]
    public $password;

    public $password_confirmation;

    /**
     * Mount the component and grab route parameters.
     */
    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    /**
     * Handle the password reset.
     */
    public function resetPassword()
    {
        $this->validate();

        // Use the Password facade to reset the password
        $status = Password::broker()->reset(
            $this->only(['email', 'password', 'password_confirmation', 'token']),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Redirect to login with a success message
            return redirect()->route('login')->with('status', __($status));
        }

        // If it fails, add the error (e.g., invalid token, invalid user)
        $this->addError('email', __($status));
    }
    public function render()
    {
        return view('livewire.auth.reset');
    }
}
