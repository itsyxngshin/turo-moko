<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseEnrollee;

class Enrollees extends Component
{
    use WithPagination;

    public $search = '';
    
    // MODAL STATES
    public $showDropModal = false;
    public $selectedEnrolleeId = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // 1. Trigger the Modal
    public function confirmDrop($id)
    {
        $this->selectedEnrolleeId = $id;
        $this->showDropModal = true;
    }

    // 2. Execute the Drop Action
    public function dropEnrollee()
    {
        if ($this->selectedEnrolleeId) {
            $record = CourseEnrollee::find($this->selectedEnrolleeId);
            
            if ($record) {
                // Update status to 'Dropped'
                $record->update(['status' => 'Dropped']);
                session()->flash('message', 'Student has been successfully dropped.');
            }
        }

        // Close modal and reset
        $this->closeModal();
    }

    // 3. Close Modal
    public function closeModal()
    {
        $this->showDropModal = false;
        $this->selectedEnrolleeId = null;
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

        return view('livewire.admin.enrollees', [
            'enrollees' => $query->latest('enrollment_date')->paginate(10)
        ])->layout('layouts.layout');
    }
}