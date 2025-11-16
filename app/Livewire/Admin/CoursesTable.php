<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;
use App\Models\User;

class CoursesTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
{
    $courses = \App\Models\Course::with(['implementer.profile', 'category'])
        ->when($this->search, function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('background', 'like', "%{$this->search}%");
        })
        ->paginate(10);

    return view('livewire.admin.courses-table', [
        'courses' => $courses,
    ]);
}

}
