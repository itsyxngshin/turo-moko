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
use Illuminate\Support\Str;
use App\Mail\VerificationCodeMail;
use App\Mail\ImplementorCredentialsMail;

class AddImplementor extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $viewMode = 'manual'; // 'manual' or 'bulk'

    // Manual Form Fields
    public $first_name, $middle_name, $last_name;
    public $phonenum, $email, $username;
    public $password, $password_confirmation;
    public $photo;

    // Bulk Import Field
    public $csvFile;

    public $alert = [
        'show' => false,
        'type' => '',
        'message' => ''
    ];

    #[On('open-add-implementor')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->viewMode = 'manual';
        $this->resetAlert();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(); 
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

    public function toggleMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetValidation(); // Clear errors when switching tabs
        $this->resetAlert();
        $this->reset(['photo', 'csvFile', 'first_name', 'last_name', 'email', 'phonenum', 'username', 'password', 'password_confirmation']);
    }

    // --- BULK IMPORT LOGIC ---

    public function downloadTemplate()
    {
        return response()->streamDownload(function () {
            echo "First Name,Middle Name,Last Name,Email,Phone,Username\n";
            echo "Juan,Dela,Cruz,juan@gmail.com,9123456789,juandc";
        }, 'implementor-template.csv');
    }

    public function importCsv()
    {
        $this->resetAlert();
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $path = $this->csvFile->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Remove header if it exists (basic check if first row contains "Email")
            if (isset($data[0]) && in_array('Email', $data[0])) {
                array_shift($data);
            }

            $count = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                // Skip empty rows or rows with insufficient columns
                if (count($row) < 6) continue;

                $firstName  = trim($row[0]);
                $middleName = trim($row[1]);
                $lastName   = trim($row[2]);
                $email      = trim($row[3]);
                $phone      = trim($row[4]); // Expected format: 9xxxxxxxxx
                $username   = trim($row[5]);

                // Basic Validation per row
                if (User::where('email', $email)->exists() || User::where('username', $username)->exists()) {
                    $errors[] = "Row " . ($index + 2) . ": $email or $username already exists.";
                    continue;
                }

                // Generate Random Password
                $generatedPassword = Str::password(10, true, true, true, false); 
                $formattedPhone = '+63' . $phone;

                // Create Profile
                $profile = Profile::create([
                    'photo_id' => null, // No photo for bulk import
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'status' => 'Active',
                ]);

                // Create User
                $user = User::create([
                    'email' => $email,
                    'phonenum' => $formattedPhone,
                    'username' => $username,
                    'password' => bcrypt($generatedPassword),
                    'profile_id' => $profile->id,
                    'role_id' => 2, // Implementor Role
                ]);

                // Send Credentials Email
                Mail::to($email)->send(new ImplementorCredentialsMail(
                    $firstName, $username, $email, $generatedPassword
                ));

                $count++;
            }

            if (count($errors) > 0) {
                // If there were specific row errors, throw exception to rollback everything
                // so the user can fix the CSV and try again cleanly.
                throw new \Exception("Import failed. Issues found: " . implode(" | ", $errors));
            }

            Log::create([
                'user_id' => Auth::id(),
                'action' => 'admin.bulk_create_implementor',
                'description' => "Bulk imported $count implementors.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            $this->reset(['csvFile']);
            $this->alert = [
                'show' => true,
                'type' => 'success',
                'message' => "Successfully imported $count implementors! Credentials sent via email."
            ];
            $this->dispatch('implementor-saved');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert = [
                'show' => true,
                'type' => 'error',
                'message' => 'Import Error: ' . $e->getMessage()
            ];
        }
    }

    // --- MANUAL ENTRY LOGIC ---

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
            Mail::to($this->email)->send(new ImplementorCredentialsMail(
                $this->first_name, $this->username, $this->email, $this->password
            ));
            
            $code = rand(100000, 999999); 
            Mail::to($user->email)->send(new VerificationCodeMail($code));

            DB::commit();

            // Success State
            $this->reset(['first_name', 'middle_name', 'last_name', 'phonenum', 'email', 'username', 'password', 'password_confirmation', 'photo']);
            
            $this->alert = [
                'show' => true,
                'type' => 'success',
                'message' => "Implementor account created successfully! Credentials sent to email."
            ];

            $this->dispatch('implementor-saved', message: "Implementor '{$this->username}' created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Add Implementor Error: ' . $e->getMessage());
            
            $this->alert = [
                'show' => true,
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function render()
    {
        return view('livewire.admin.modal.add-implementor');
    }
}