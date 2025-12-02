<?php
namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class ImplementorDashboard extends Component
{
    public $courses;
    public $instructor;
    public $enrolleesCount;
    public $submissionsCount;
    public $evaluationsCount;

    public function mount()
    {
        // Example: hardcoded instructor ID = 2
        $this->instructor = User::where('id', 2)->where('role_id', 2)->first();

        // Fetch all courses for this instructor
        $this->courses = $this->instructor
            ? Course::where('implementer_id', $this->instructor->id)->get()
            : collect();

        // Fetch counts
        $this->enrolleesCount = DB::table('course_enrollees')->count();
        $this->submissionsCount = DB::table('submissions')->count();
        $this->evaluationsCount = DB::table('evaluations')->count();
    }

    public function render()
    {
        return view('livewire.implementors.implementor-dashboard');
    }
}