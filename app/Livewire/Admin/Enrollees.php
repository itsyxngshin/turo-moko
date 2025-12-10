<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Enrollees extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $enrollees = DB::table('course_enrollees')
            ->join('users', 'course_enrollees.enrollee_id', '=', 'users.id')
            ->join('profiles', 'users.profile_id', '=', 'profiles.id')
            ->leftJoin('media', 'profiles.photo_id', '=', 'media.id')
            ->join('courses', 'course_enrollees.course_id', '=', 'courses.id')
            ->select(
                'course_enrollees.id',
                'profiles.first_name',
                'profiles.middle_name',
                'profiles.last_name',
                'media.file_path as photo',
                'courses.course_name',
                'course_enrollees.enrollment_date',
                'course_enrollees.completion_date',
                'course_enrollees.status'
            )
            ->where(function ($q) {
                $q->where('profiles.first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('profiles.last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('courses.course_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('course_enrollees.id', 'desc')
            ->paginate(10);

        return view('livewire.admin.enrollees', [
            'enrollees' => $enrollees
        ]);
    }
}