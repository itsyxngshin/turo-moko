<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseEnrollee;
use App\Models\User;
use App\Models\Profile;

class Enrollees extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

   public function render()
{
    $query = CourseEnrollee::query()
        ->with(['user.profile.photo', 'course']); // Load relationships

    if ($this->search) {
        $query->whereHas('user.profile', function($q) {
            $q->where('first_name', 'like', "%{$this->search}%")
              ->orWhere('last_name', 'like', "%{$this->search}%");
        })
        ->orWhereHas('course', function($q) {
            $q->where('course_title', 'like', "%{$this->search}%");
        });
    }

    $enrollees = $query->latest('enrollment_date')->paginate(10);

    return view('livewire.admin.enrollees', [
        'enrollees' => $enrollees
    ])->layout('layouts.layout');
}

}
