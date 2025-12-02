<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course; 
use App\Models\Photo; 
use Livewire\Attributes\Layout;

// make sure you import your Photo model if you have one defined for the relationship
#[Layout('layouts.layout4')]
class CourseMenu extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'latest'; // Default sort option

    public function updatedSearch() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function render()
    {
        $query = Course::query()
            ->with(['coverPhoto', 'category', 'organization', 'tags'])
            ->where('status', 'Active')
            ->where('visibility', 'Visible');

        //ADVANCED SEARCH LOGIC
        if ($this->search) {
            $query->where(function($q) {
                // Search Title & Description
                $q->where('course_title', 'like', '%'.$this->search.'%')
                  ->orWhere('background', 'like', '%'.$this->search.'%')
                  
                  // Search Category Name
                  ->orWhereHas('category', function($subQ) {
                      $subQ->where('category_name', 'like', '%'.$this->search.'%');
                  })
                  
                  // Search Organization Name
                  ->orWhereHas('organization', function($subQ) {
                      $subQ->where('name', 'like', '%'.$this->search.'%');
                  })
                  
                  // Search Tags
                  ->orWhereHas('tags', function($subQ) {
                      $subQ->where('tag', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // SORTING LOGIC
        switch ($this->sort) {
            case 'oldest':
                $query->oldest('start_date');
                break;
            case 'a-z':
                $query->orderBy('course_title', 'asc');
                break;
            case 'z-a':
                $query->orderBy('course_title', 'desc');
                break;
            default: // 'latest'
                $query->latest('start_date');
                break;
        }

        return view('livewire.learner.course-menu', [
            'courses' => $query->paginate(8)
        ]);
    }
}