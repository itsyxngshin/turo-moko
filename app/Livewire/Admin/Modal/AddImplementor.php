<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On; // Import this to listen for events
use App\Models\User;
use App\Models\Profile;
use App\Models\Photo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddImplementor extends Component
{
    use WithFileUploads;

    public $isOpen = false; // Controls visibility

    public $first_name, $middle_name, $last_name;
    public $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo;

    // Listen for the event dispatched from the parent table
    #[On('open-add-implementor')]
    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(); // Clears all fields
        $this->resetValidation();
    }

    public function removePhoto()
    {
        $this->photo = null;
    }

    public function save()
    {
        $this->validate([
            'photo' => 'nullable|image|max:1024',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phonenum' => 'required|unique:users,phonenum',
            'username' => 'required|unique:users,username',
            'password' => [
                'required',
                'min:8',
                'same:password_confirmation',
                'regex:/[A-Z]/', // at least one uppercase
                'regex:/[a-z]/', // at least one lowercase
                'regex:/[0-9]/', // at least one number
                'regex:/[@$!%*#?&]/' // at least one special char
            ],
        ]);

        try {
            // Save uploaded photo
            $photoId = null;
            if ($this->photo) {
                $photoPath = $this->photo->store('photos', 'public');
                $photo = \App\Models\Photo::create(['photos' => $photoPath]);
                $photoId = $photo->id;
            }

            // Create profile
            $profile = \App\Models\Profile::create([
                'photo_id' => $photoId,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
            ]);

            // Create user
            \App\Models\User::create([
                'email' => $this->email,
                'phonenum' => $this->phonenum,
                'username' => $this->username,
                'password' => bcrypt($this->password),
                'profile_id' => $profile->id,
                'role_id' => 2, // Implementor Role
            ]);

            $this->dispatch('implementor-saved');
            $this->closeModal();

        } catch (\Exception $e) {
            // Ideally, log this error instead of dumping it in production
            $this->addError('email', 'Error saving user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.modal.add-implementor');
    }
}