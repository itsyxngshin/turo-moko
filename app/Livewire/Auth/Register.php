<?php

namespace App\Livewire\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;
use App\Models\Role; 
use App\Models\Profile; 
use Livewire\Component;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Hash; // Import Hash facade

class Register extends Component
{
    public string $roleName = 'implementor'; 

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
     * This avoids storing a separate roleId and keeps the data consistent.
     * The result is cached for the lifecycle of a single request.
     */

    public function setRole(string $role)
    {
        // Basic validation to ensure only allowed roles can be set.
        if (in_array($role, ['implementor', 'learner'])) {
            $this->roleName = $role;
        }
    }

    #[Computed]
    public function role(): ?Role{
        return Role::where('role_name', $this->roleName)->first();
    }
    
    /**
     * The main registration method triggered on form submission.
     */
    public function register()
    {
        $validated = $this->validate();

        if (!$this->role) {
            $this->dispatch('swal-error', [
                'title' => 'Invalid Role',
                'text' => 'The selected role is not valid.',
                'icon' => 'error',
            ]);
            return;
        }

        $user = DB::transaction(function () use ($validated) {
            // Create the profile first
            $profile = Profile::create([
                'first_name' => $validated['firstName'],
                'middle_name' => $validated['middleName'],
                'last_name' => $validated['lastName'],
            ]);

            // Create the user and link it to the new profile
            return User::create([
                'email' => $validated['email'],
                'username' => $validated['username'],
                'phonenum' => $validated['phonenum'],
                'password' => Hash::make($validated['password']),
                'role_id' => $this->role->id,
                'profile_id' => $profile->id,
            ]);
        });

        // Eager load the profile relationship for the welcome message.
        $user->load('profile');

        //Log-in the user
        Auth::login($user);

        // Create the dynamic URL
        $redirectUrl = '/' . $user->role->role_name . '/dashboard';

        //Dispatch SweetAlert2 success notification and redirect event
        $this->dispatch('swal-redirect', [
            'title' => 'Registration Successful!',
            'text'  => 'Welcome! You will be redirected to your dashboard.',
            'icon'  => 'success',
            'url'   => route($redirectUrl) // Or whatever your target route is
        ]);
    }
    public function render()
    {
        return view('livewire.auth.register');
    }
}
