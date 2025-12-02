<?php

namespace App\Livewire\Implementor;

use Livewire\Component;
use App\Models\Course; // check course model deets
use Livewire\WithPagination; //opt

class ShowCourses extends Component
{
    use WithPagination;

    public $search = '';
    public $filterSemester = 'All';
    public $viewType = 'Card';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Course::query();

        // Sorting by most recently used/opened
        if ($this->filterRecent) { 
            $query->orderBy('updated_at', 'desc'); 
        }

        // Searching by course name or category
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('category', 'like', "%{$this->search}%");
            });
        }


        $courses = $query->latest()->paginate(6);

        return view('livewire.implementor.show-courses', [
            'courses' => $courses,
        ]);
    }
}
