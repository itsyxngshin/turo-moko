<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On; 
use App\Models\User;
use App\Models\Photo;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ModifyImplementor extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $implementorId;
    public $alert = ['show' => false, 'type' => '', 'message' => ''];

    public $first_name, $middle_name, $last_name, $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo, $existingPhoto;

    #[On('modify-implementor')] 
    public function loadImplementor($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['password', 'password_confirmation', 'photo']);

        $this->implementorId = $id;

        $user = User::with('profile.photo')->find($id);
        
        if ($user) {
            $this->first_name = $user->profile->first_name ?? '';
            $this->middle_name = $user->profile->middle_name ?? '';
            $this->last_name = $user->profile->last_name ?? '';
            
            // LOGIC: Strip +63 so input only shows the 10 digits
            // Example: +639123... -> 9123...
            $this->phonenum = str_replace('+63', '', $user->phonenum ?? '');

            $this->email = $user->email ?? '';
            $this->username = $user->username ?? '';
            $this->existingPhoto = $user->profile?->photo?->photos ?? null;

            $this->alert = ['show' => false, 'type' => '', 'message' => '']; // Reset alert on open
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['photo', 'password', 'password_confirmation']);
    }

    public function update()
    {
        $this->alert = ['show' => false, 'type' => '', 'message' => ''];
        // Validation Rules
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'ends_with:@gmail.com,@yahoo.com,@turo-moko.com', Rule::unique('users')->ignore($this->implementorId)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->implementorId)],
            'phonenum' => ['required', 'regex:/^9\d{9}$/', Rule::unique('users')->ignore($this->implementorId)],
            'password' => [
                'nullable', // Optional for edit
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*#?&]/' 
            ],
            'photo' => 'nullable|image|max:3072',
        ], [
            'phonenum.regex' => 'Please enter a valid number starting with 9 (10 digits).',
            'email.ends_with' => 'Email must be @gmail.com or @yahoo.com.',
        ]);

        try {
            $user = User::with('profile')->find($this->implementorId);
            if (!$user) return;

            // 1. Format Phone Number
            $formattedPhone = '+63' . $this->phonenum;

            // 2. Prepare User Update Data
            $userData = [
                'username' => $this->username,
                'email' => $this->email,
                'phonenum' => $formattedPhone,
            ];

            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            // 3. Update Profile
            if ($user->profile) {
                $user->profile->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                ]);

                // 4. Handle Photo
                if ($this->photo) {
                    // Delete old photo if exists
                    if ($user->profile->photo && Storage::disk('public')->exists($user->profile->photo->photos)) {
                        Storage::disk('public')->delete($user->profile->photo->photos);
                    }

                    $path = $this->photo->store('implementor/photos', 'public');
                    
                    if ($user->profile->photo) {
                        $user->profile->photo->update(['photos' => $path]);
                    } else {
                        $photo = Photo::create(['photos' => $path]);
                        $user->profile->update(['photo_id' => $photo->id]);
                    }
                }
            }

            // 5. Create Log
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'admin.update_implementor',
                'description' => "Updated profile for implementor '{$this->username}'.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => ['updated_user_id' => $user->id]
            ]);

            // 6. Notify Success
            $this->dispatch('swal-notify', [
                'icon' => 'success',
                'title' => 'Updated Successfully',
                'text' => "Implementor '{$this->username}' details have been saved."
            ]);
            
            $this->dispatch('implementor-updated', message: "Implementor '{$this->username}' updated successfully.");
            $this->closeModal();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Implementor Failed: ' . $e->getMessage());

            $this->dispatch('swal-notify', [
                'icon' => 'error',
                'title' => 'Update Failed',
                'text' => 'An error occurred while updating. Please try again.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.modal.modify-implementor');
    }
}