<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On; 
use App\Models\User;
use App\Models\Photo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ModifyImplementor extends Component
{
    use WithFileUploads;

    public $showModal = false; // Visibility State
    public $implementorId;

    public $first_name, $middle_name, $last_name, $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo, $existingPhoto;

    // Listen for the event sent by the table
    #[On('modify-implementor')] 
    public function loadImplementor($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        // Reset inputs to avoid stale data
        $this->reset(['password', 'password_confirmation', 'photo', 'first_name', 'middle_name', 'last_name', 'phonenum', 'email', 'username', 'existingPhoto']);

        $this->implementorId = $id;

        $user = User::with('profile.photo')->find($id);
        
        if ($user) {
            $this->first_name = $user->profile->first_name ?? '';
            $this->middle_name = $user->profile->middle_name ?? '';
            $this->last_name = $user->profile->last_name ?? '';
            $this->phonenum = $user->phonenum ?? '';
            $this->email = $user->email ?? '';
            $this->username = $user->username ?? '';
            
            // Get the existing photo path
            $this->existingPhoto = $user->profile?->photo?->photos ?? null;

            $this->showModal = true; // Shows the modal
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['photo', 'password', 'password_confirmation']);
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->implementorId)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->implementorId)],
            'phonenum' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($this->implementorId)],
            'password' => 'nullable|confirmed|min:8',
            'photo' => 'nullable|image|max:1024',
        ]);

        $user = User::with('profile')->find($this->implementorId);
        if (!$user) return;

        // 1. Update User
        $userData = [
            'username' => $this->username,
            'email' => $this->email,
            'phonenum' => $this->phonenum,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        // 2. Update Profile
        if ($user->profile) {
            $user->profile->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
            ]);

            // 3. Handle Photo
            if ($this->photo) {
                // Delete old photo if exists
                if ($user->profile->photo && Storage::disk('public')->exists($user->profile->photo->photos)) {
                    Storage::disk('public')->delete($user->profile->photo->photos);
                }

                $path = $this->photo->store('implementor/photos', 'public');
                
                // Check if user has a photo record, update or create
                if ($user->profile->photo) {
                    $user->profile->photo->update(['photos' => $path]);
                } else {
                    $photo = Photo::create(['photos' => $path]);
                    $user->profile->update(['photo_id' => $photo->id]);
                }
            }
        }

        $this->dispatch('implementor-updated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.modal.modify-implementor');
    }
}