<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\CourseEnrollee;
use App\Models\Photo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Enrollees extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    // MODAL STATES
    public $showDropModal = false;
    public $showUndropModal = false;
    public $showEditModal = false;

    // IDs for tracking
    public $selectedEnrolleeId = null;
    public $selectedUserId = null; 

    // EDIT FORM PROPERTIES
    public $edit_first_name;
    public $edit_middle_name;
    public $edit_last_name;
    public $edit_email;
    public $edit_username;
    public $edit_phonenum;
    public $edit_password;
    public $showCoursesModal = false;
public $selectedStudentCourses;
public $selectedStudentName;
    
    // Photo handling
    public $edit_photo;      // The new file being uploaded
    public $existing_photo;  // The path string of the current photo

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // ==========================================
    // DROP & UNDROP ACTIONS
    // ==========================================
    public function confirmDrop($id)
    {
        $this->selectedEnrolleeId = $id;
        $this->showDropModal = true;
    }

    public function dropEnrollee()
    {
        if ($this->selectedEnrolleeId) {
            $record = CourseEnrollee::find($this->selectedEnrolleeId);
            if ($record) {
                $record->update(['status' => 'Dropped']);
                session()->flash('message', 'Student has been successfully dropped.');
            }
        }
        $this->closeModal();
    }

    public function confirmUndrop($id)
    {
        $this->selectedEnrolleeId = $id;
        $this->showUndropModal = true;
    }

    public function undropEnrollee()
    {
        if ($this->selectedEnrolleeId) {
            $record = CourseEnrollee::find($this->selectedEnrolleeId);
            if ($record) {
                $record->update(['status' => 'Active']);
                session()->flash('message', 'Student status restored to Active.');
            }
        }
        $this->closeModal();
    }

    // ==========================================
    // EDIT ACTIONS
    // ==========================================
    public function editEnrollee($id)
    {
        $this->resetValidation();
        $this->reset(['edit_photo', 'edit_password', 'existing_photo']);

        $this->selectedEnrolleeId = $id;
        
        // Eager load relationships
        $record = CourseEnrollee::with('enrollee.profile.photo')->find($id);

        if ($record && $record->enrollee) {
            $this->selectedUserId = $record->enrollee->id;

            // 1. Fill User Table Data
            $this->edit_email = $record->enrollee->email;
            $this->edit_username = $record->enrollee->username;
            // Remove +63 for the input field
            $this->edit_phonenum = str_replace('+63', '', $record->enrollee->phonenum ?? '');

            // 2. Fill Profile Table Data
            if ($record->enrollee->profile) {
                $this->edit_first_name = $record->enrollee->profile->first_name;
                $this->edit_middle_name = $record->enrollee->profile->middle_name;
                $this->edit_last_name = $record->enrollee->profile->last_name;
                
                // 3. Handle Photo Preview
                $this->existing_photo = $record->enrollee->profile->photo?->photos ?? null;
            }

            $this->showEditModal = true;
        }
    }

    public function updateEnrollee()
    {
        $this->validate([
            'edit_first_name' => 'required|string|max:255',
            'edit_last_name'  => 'required|string|max:255',
            'edit_middle_name'=> 'nullable|string|max:255',
            // Unique validation ignoring current user
            'edit_email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($this->selectedUserId)],
            'edit_username'   => ['required', 'string', Rule::unique('users', 'username')->ignore($this->selectedUserId)],
            'edit_phonenum'   => ['nullable', 'numeric', 'digits:10'], // e.g. 9123456789
            'edit_password'   => 'nullable|min:8',
            'edit_photo'      => 'nullable|image|max:2048', // 2MB Max
        ]);

        $record = CourseEnrollee::with('enrollee.profile')->find($this->selectedEnrolleeId);
        $user = $record->enrollee;

        if ($user) {
            // 1. Update User Record
            $userData = [
                'email'    => $this->edit_email,
                'username' => $this->edit_username,
                'phonenum' => '+63' . $this->edit_phonenum,
            ];

            // Only hash and update password if a new one was typed
            if (!empty($this->edit_password)) {
                $userData['password'] = Hash::make($this->edit_password);
            }

            $user->update($userData);

            // 2. Update Profile Record
            if ($user->profile) {
                $user->profile->update([
                    'first_name'  => $this->edit_first_name,
                    'middle_name' => $this->edit_middle_name,
                    'last_name'   => $this->edit_last_name,
                ]);

                // 3. Handle Photo Upload
                if ($this->edit_photo) {
                    // Delete old photo if exists
                    if ($user->profile->photo && $user->profile->photo->photos) {
                        if (Storage::disk('public')->exists($user->profile->photo->photos)) {
                            Storage::disk('public')->delete($user->profile->photo->photos);
                        }
                    }

                    // Upload new photo
                    $path = $this->edit_photo->store('profile-photos', 'public');

                    if ($user->profile->photo) {
                        $user->profile->photo->update(['photos' => $path]);
                    } else {
                        $photo = Photo::create(['photos' => $path]);
                        $user->profile->update(['photo_id' => $photo->id]);
                    }
                }
            }

            session()->flash('message', 'Student information updated successfully.');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showDropModal = false;
        $this->showUndropModal = false;
        $this->showEditModal = false;
        
        $this->selectedEnrolleeId = null;
        $this->selectedUserId = null;

        // Reset inputs
        $this->reset([
            'edit_first_name', 'edit_middle_name', 'edit_last_name',
            'edit_email', 'edit_username', 'edit_phonenum', 
            'edit_password', 'edit_photo', 'existing_photo'
        ]);
    }

public function render()
{
    $query = CourseEnrollee::query()
        ->with(['enrollee.profile.photo', 'course']);

    if (!empty($this->search)) {
        $query->whereHas('enrollee.profile', function ($q) {
            $q->where('first_name', 'like', '%' . $this->search . '%')
              ->orWhere('last_name', 'like', '%' . $this->search . '%');
        })->orWhereHas('course', function ($q) {
            $q->where('course_title', 'like', '%' . $this->search . '%');
        });
    }

    // GET ALL ENROLLEES FOR GROUPING (avoid duplicate rows)
    $allEnrollees = $query->latest('enrollment_date')->get();
    $enrolleesByStudent = $allEnrollees->groupBy('enrollee_id');

    // PAGINATION: slice the grouped collection manually
    $perPage = 10;
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $items = $enrolleesByStudent->values(); // reindex collection
    $paginatedEnrolleesByStudent = new \Illuminate\Pagination\LengthAwarePaginator(
        $items->slice(($currentPage - 1) * $perPage, $perPage),
        $items->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('livewire.admin.enrollees', [
        'enrolleesByStudent' => $paginatedEnrolleesByStudent,
        'enrollees' => $paginatedEnrolleesByStudent, // for links()
    ])->layout('layouts.layout');
}

public function viewCourses($enrolleeId)
{
    $this->selectedEnrolleeId = $enrolleeId; // Set selectedEnrolleeId
    $this->showCoursesModal = true;

    $student = CourseEnrollee::with('enrollee.profile.photo', 'course')
        ->where('enrollee_id', $enrolleeId)
        ->get();

    $this->selectedStudentCourses = $student;
    $this->selectedStudentName = $student->first()?->enrollee->profile->first_name . ' ' . $student->first()?->enrollee->profile->last_name;
}

}