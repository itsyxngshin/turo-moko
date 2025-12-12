<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Role; 
use App\Models\Profile; 
use App\Models\Log; // <--- IMPORT THIS
use Livewire\Component;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Notification;

#[Layout('layouts.auth')]
class Register extends Component
{
    public string $roleName = 'learner'; // Default role

    #[Rule('required|string|max:255')]
    public string $firstName = '';

    #[Rule('nullable|string|max:255')]
    public string $middleName = '';

    #[Rule('required|string|max:255')]
    public string $lastName = '';

    #[Rule('required|string|email|max:255|unique:users')]
    public string $email = '';

    #[Rule('required|string|max:255|unique:users')]
    public string $username = '';

    // Simple phone validation (customize as needed for your specific format)
    #[Rule('required|string|regex:/^\+63\d{10}$/|max:15|unique:users')]
    public string $phonenum = '';

    #[Rule('required|string|min:8')]
    public string $password = '';

    #[Rule('required|string|min:8|same:password')]
    public string $password_confirmation = '';

    /**
     * A computed property to fetch the Role model based on the selected role name.
     */

    /*
    *public function setRole(string $role)
    *{
    *    if (in_array($role, ['implementor', 'learner'])) {
    *        $this->roleName = $role;
    *    }
    *}

    */

    #[Computed]
    public function role(): ?Role{
        return Role::where('role_name', $this->roleName)->first();
    }
    
    #[Computed]
    public function passwordStrength()
    {
        $password = $this->password;
        $score = 0;

        if (empty($password)) {
            return [
                'strength' => '',
                'color'    => 'bg-gray-200',
                'width'    => '0%'
            ];
        }

        if (strlen($password) >= 8) $score++;      
        if (strlen($password) >= 12) $score++;     
        if (preg_match('/[a-z]/', $password)) $score++; 
        if (preg_match('/[A-Z]/', $password)) $score++; 
        if (preg_match('/[0-9]/', $password)) $score++; 
        if (preg_match('/[\W_]/', $password)) $score++; 

        switch ($score) {
            case 0:
            case 1:
            case 2:
                return ['strength' => 'Weak', 'color' => 'bg-red-500', 'width' => '33%'];
            case 3:
            case 4:
                return ['strength' => 'Medium', 'color' => 'bg-orange-500', 'width' => '66%'];
            case 5:
            case 6:
                return ['strength' => 'Strong', 'color' => 'bg-green-500', 'width' => '99%'];
            default:
                return ['strength' => 'Weak', 'color' => 'bg-red-500', 'width' => '33%'];
        }
    }
    
    public function register()
    {
        $validated = $this->validate();

        if (!$this->role) {
            // [LOGGING]: Log attempt with invalid role
            Log::create([
                'user_id' => null,
                'action' => 'auth.register_failed',
                'description' => 'Registration failed: Invalid role selected or role not found.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => ['attempted_role' => $this->roleName]
            ]);

            $this->dispatch('swal-error', [
                'title' => 'Invalid Role',
                'text' => 'The selected role is not valid.',
                'icon' => 'error',
            ]);
            return;
        }

        $code = rand(100000, 999999); 

        // Use try-catch to log unexpected database errors (Optional but recommended)
        try {

            $admins = User::whereHas('role', function ($query) {
                $query->where('role_name', 'admin');
            })->get();

            $user = DB::transaction(function () use ($validated, $code) {
                
                $profile = Profile::create([
                    'first_name' => $validated['firstName'],
                    'middle_name' => $validated['middleName'],
                    'last_name' => $validated['lastName'],
                ]);

                return User::create([
                    'email' => $validated['email'],
                    'username' => $validated['username'],
                    'phonenum' => $validated['phonenum'],
                    'password' => Hash::make($validated['password']),
                    'role_id' => $this->role->id,
                    'profile_id' => $profile->id,
                    'verification_code' => $code, 
                ]);
            });

            // [LOGGING]: SUCCESSFUL REGISTRATION
            // We place this AFTER the transaction ensures the user actually exists
            Log::create([
                'user_id' => $user->id,
                'action' => 'auth.register',
                'description' => 'New user registered successfully.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => [
                    'role' => $this->roleName,
                    'email_domain' => substr(strrchr($validated['email'], "@"), 1) // Analytics: track which email providers are used
                ]
            ]);

            $user->load('profile');

            Mail::to($user->email)->send(new VerificationCodeMail($code));

            $identify = $user->role->role_name ?? 'learner';
        
            $match = match($identify) {
                'implementer' => route('admin.implementors'), 
                'learner' => route('admin.enrollees'),
                default => route('homepage'),
            };

            $notification = new \App\Notifications\GeneralNotification(
                'Registration Successful', 
                "The user {$user->username} has been registered and ready for verification.", 
                route('admin.implementors') // Link admins to the list
            );

            // 3. Send to all admins at once using the Facade
            Notification::send($admins, $notification);

            Auth::login($user);
            
            session()->flash('swal:success', [
                'title' => 'Registration Successful!',
                'text' => 'We\'ve sent a verification link to your email.',
            ]);

            return $this->redirect(route('verification.notice'), navigate: true);

        } 
        catch (\Exception $e) {
            $admins = User::whereHas('role', function ($query) {
                $query->where('role_name', 'admin');
            })->get();

            $notification = new \App\Notifications\GeneralNotification(
                'Registration Error', 
                "An error was detected during a registration process. See the logs", 
                route('admin.settings') // Link admins to the list
            );

            // 3. Send to all admins at once using the Facade
            Notification::send($admins, $notification);

            // [LOGGING]: CRITICAL ERROR
            // This captures if the DB Transaction fails
            Log::create([
                'user_id' => null,
                'action' => 'auth.register_error',
                'description' => 'Database error during registration.',
                'ip_address' => request()->ip(),
                'properties' => [
                    'error_message' => $e->getMessage(),
                    'email_attempt' => $this->email
                ]
            ]);

            // Re-throw the error so Livewire/Laravel handles the UI feedback usually
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}