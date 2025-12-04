<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Profile;
use App\Models\Photo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ModifyImplementor extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $implementorId;

    public $first_name, $middle_name, $last_name, $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo, $existingPhoto;

    protected $listeners = ['modify-implementor' => 'loadImplementor'];

    public function loadImplementor($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->implementorId = $id;

        $user = User::with('profile.photo')->find($id);
        if ($user) {
            $this->first_name = $user->profile->first_name ?? '';
            $this->middle_name = $user->profile->middle_name ?? '';
            $this->last_name = $user->profile->last_name ?? '';
            $this->phonenum = $user->phonenum ?? '';
            $this->email = $user->email ?? '';
            $this->username = $user->username ?? '';
            $this->existingPhoto = $user->profile?->photo?->photos ?? null;

            $this->showModal = true;
        }
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'username' => 'required|string|max:255',
            'phonenum' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:8',
            'photo' => 'nullable|image|max:1024', // 1MB max
        ]);

        $user = User::with('profile')->find($this->implementorId);
        if (!$user) return;

        // Update user info
        $user->update([
            'username' => $this->username,
            'email' => $this->email,
            'phonenum' => $this->phonenum,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        // Update profile info
        $profile = $user->profile;
        if ($profile) {
            $profile->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
            ]);

            // Handle photo upload
            if ($this->photo) {
                // Delete old photo
                if ($profile->photo && Storage::disk('public')->exists($profile->photo->photos)) {
                    Storage::disk('public')->delete($profile->photo->photos);
                }

                // Save new photo
                $path = $this->photo->store('implementor/photos', 'public');
                $photo = Photo::create(['photos' => $path]);

                // Update profile photo_id
                $profile->update(['photo_id' => $photo->id]);
            }
        }

        $this->dispatch('implementor-updated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.modal.modify-implementor');
    }
}
