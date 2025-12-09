<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course as CourseModel; // alias here
use App\Models\coverPhoto;

class Course extends Component
{
    public $course;

    public function mount($courseId = null)
{
    $this->course = $courseId
        ? CourseModel::with(['lessons', 'quizzes', 'activeCoverPhoto'])->find($courseId)
        : CourseModel::with(['lessons', 'quizzes', 'activeCoverPhoto'])->first();
}


    public function render()
    {
        return view('livewire.learner.course');
    }
}
