<?php

namespace App\Livewire\Implementors;

    use Livewire\Component;
    use App\Models\User;
    use App\Models\Course;
    use App\Models\Submissions;
    use App\Models\Evaluation;
    use Illuminate\Support\Facades\Auth;

    class ImplementorDashboard extends Component
    {
        public $courses;
        public $instructor;
        public $enrolleesCount;
        public $submissionsCount;
        public $evaluationsCount;
        public $featuredCourse;

        public function mount()
        {
            // Hardcode instructor id = 2 for now
            $this->instructor = User::where('id', 2)
                ->where('role_id', 2)
                ->first();

            
            // Dashboard stats
            $this->enrolleesCount = User::where('role_id', 1)->count();
            $this->submissionsCount = Submissions::count();
            $this->evaluationsCount = Evaluation::count();
            $this->featuredCourse = Course::latest()->first();
        }

        public function render()
        {
            return view('livewire.implementors.implementor-dashboard');
        }
    }
