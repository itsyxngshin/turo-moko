<?php

namespace App\Livewire\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Role; 
use App\Models\Profile; 
use Livewire\Component;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Hash; // Import Hash facade

#[Layout('layouts.auth')]
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
    
    #[Computed]
    public function passwordStrength()
    {
        $password = $this->password;
        $score = 0;

        // Return empty array if password is empty
        if (empty($password)) {
            return [
                'strength' => '',
                'color'    => 'bg-gray-200',
                'width'    => '0%'
            ];
        }

        // Add points for criteria
        if (strlen($password) >= 8) $score++;      // Length 8+
        if (strlen($password) >= 12) $score++;     // Length 12+
        if (preg_match('/[a-z]/', $password)) $score++; // Lowercase
        if (preg_match('/[A-Z]/', $password)) $score++; // Uppercase
        if (preg_match('/[0-9]/', $password)) $score++; // Numbers
        if (preg_match('/[\W_]/', $password)) $score++; // Symbols (non-word chars)

        // Determine strength based on score
        switch ($score) {
            case 0:
            case 1:
            case 2:
                return [
                    'strength' => 'Weak',
                    'color'    => 'bg-red-500',
                    'width'    => '33%'
                ];
            case 3:
            case 4:
                return [
                    'strength' => 'Medium',
                    'color'    => 'bg-orange-500',
                    'width'    => '66%'
                ];
            case 5:
            case 6:
                return [
                    'strength' => 'Strong',
                    'color'    => 'bg-green-500',
                    'width'    => '99%'
                ];
            default:
                return [
                    'strength' => 'Weak',
                    'color'    => 'bg-red-500',
                    'width'    => '33%'
                ];
        }
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

        $redirect = match ($user->role->role_name) {
                'admin' => route('admin.hub'),
                'learner' => route('learner.hub'),
                'implementer' => route('implementer.hub'),
                default => route('homepage'),
            };

        //Dispatch SweetAlert2 success notification and redirect event
        $this->dispatch('swal-redirect', [
            'title' => 'Registration Successful!',
            'text'  => 'Welcome! You will be redirected to your dashboard.',
            'icon'  => 'success',
            'redirect'   => $redirect // Or whatever your target route is
        ]);
    }
    public function render()
    {
        return view('livewire.auth.register')->with(['layout' => 'layouts.auth']);
    }
}
