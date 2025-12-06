<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class CoursesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $course;

    protected $updatesQueryString = ['search'];

    // Reset pagination when search term changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function archiveCourse($courseId)
{
    $course = Course::find($courseId); // use find instead of findOrFail

    if (!$course) {
        session()->flash('error', 'Course not found.');
        return;
    }

    $course->update([
        'status' => 'archived',
        'visibility' => 'hidden',
    ]);

    session()->flash('message', 'Course archived successfully.');
}




    public function render()
    {
        $query = Course::query()->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', fn($q) => 
                      $q->where('category_name', 'like', '%' . $this->search . '%'));
        }

        $courses = $query->paginate(8);

        return view('livewire.admin.courses-table', [
            'courses' => $courses,
        ]);
    }
}
