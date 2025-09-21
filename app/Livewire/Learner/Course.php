<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course as CourseModel; // alias here

class Course extends Component
{
    public $course;

    public function mount($courseId = null)
    {
        $this->course = $courseId
            ? CourseModel::with(['lessons', 'quizzes'])->find($courseId)
            : CourseModel::with(['lessons', 'quizzes'])->first();
    }

    public function render()
    {
        return view('livewire.learner.course');
    }
}
