<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Profile;
use App\Models\Photo;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerificationCodeMail;
use App\Mail\ImplementorCredentialsMail;

class AddImplementor extends Component
{
    use WithFileUploads;

    public $isOpen = false;

    // Form Fields
    public $first_name, $middle_name, $last_name;
    public $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo;

    // New: Notification State
    public $alert = [
        'show' => false,
        'type' => '', // 'success' or 'error'
        'message' => ''
    ];

    #[On('open-add-implementor')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetAlert();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(); // Clears form and alerts
        $this->resetValidation();
    }

    public function resetAlert()
    {
        $this->alert = ['show' => false, 'type' => '', 'message' => ''];
    }

    public function removePhoto()
    {
        $this->photo = null;
    }

    public function save()
    {
        $this->resetAlert();
        
        $this->validate([
            'photo' => 'nullable|image|max:3072',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email|ends_with:@gmail.com,@turo-moko.com',
            'phonenum' => ['required', 'unique:users,phonenum', 'regex:/^9\d{9}$/'],
            'username' => 'required|unique:users,username',
            'password' => [
                'required', 'min:8', 'same:password_confirmation',
                'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
        ]);

        // Start Transaction
        DB::beginTransaction();

        try {
            $formattedPhone = '+63' . $this->phonenum;

            // 1. Photo
            $photoId = null;
            if ($this->photo) {
                $photoPath = $this->photo->store('photos', 'public');
                $photo = Photo::create(['photos' => $photoPath]);
                $photoId = $photo->id;
            }

            // 2. Profile
            $profile = Profile::create([
                'photo_id' => $photoId,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'status' => 'Active',
            ]);

            // 3. User
            $user = User::create([
                'email' => $this->email,
                'phonenum' => $formattedPhone,
                'username' => $this->username,
                'password' => bcrypt($this->password),
                'profile_id' => $profile->id,
                'role_id' => 2,
            ]);

            // 4. Log
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'admin.create_implementor',
                'description' => "Created implementor '{$this->username}'.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => ['created_user_id' => $user->id]
            ]);

            // 5. Send Emails
            // We do this LAST so if it fails, the catch block rolls back the User creation
            Mail::to($this->email)->send(new ImplementorCredentialsMail(
                $this->first_name, $this->username, $this->email, $this->password
            ));
            
            $code = rand(100000, 999999); 
            Mail::to($user->email)->send(new VerificationCodeMail($code));

            // Commit Transaction (Save to DB permanently)
            DB::commit();

            // SUCCESS STATE
            $this->reset(['first_name', 'middle_name', 'last_name', 'phonenum', 'email', 'username', 'password', 'password_confirmation', 'photo']);
            
            $this->alert = [
                'show' => true,
                'type' => 'success',
                'message' => "Implementor account created successfully! Credentials sent to email."
            ];

            $this->dispatch('implementor-saved', message: "Implementor '{$this->username}' created successfully!");
            
            // REMOVED: $this->resetPage(); 
            // Reason: This causes an error if the component doesn't use WithPagination trait.

        } catch (\Exception $e) {
            // Rollback Transaction (Undo DB changes if error occurred)
            DB::rollBack();

            \Illuminate\Support\Facades\Log::error('Add Implementor Error: ' . $e->getMessage());
            
            $this->alert = [
                'show' => true,
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage() // Showing message temporarily to help you debug
            ];
        }
    }

    public function render()
    {
        return view('livewire.admin.modal.add-implementor');
    }
}