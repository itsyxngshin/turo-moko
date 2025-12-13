<?php
namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use App\Models\ImplementorFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImplementorDashboard extends Component
{
    public $courses;
    public $instructor;
    public $enrolleesCount;
    public $submissionsCount;
    public $evaluationsCount;
    public $overallRating;
    public $coursesCount;

    public function mount()
    {
        // Get the currently logged-in user
        $this->instructor = Auth::user(); 
        
        // Fetch all courses for this instructor
        $this->courses = $this->instructor
            ? Course::where('implementer_id', $this->instructor->id)->get()
            : collect();

        $this->coursesCount = $this->courses->count();

        // Fetch counts
        $this->enrolleesCount = DB::table('course_enrollees')->count();
        $this->submissionsCount = DB::table('submissions')->count();
        $this->evaluationsCount = DB::table('evaluations')->count();

        // Calculate overall implementor rating (average of all implementor feedback)
        if ($this->instructor) {
            $implementorFeedbacks = ImplementorFeedback::where('implementer_id', $this->instructor->id)->get();
            
            if ($implementorFeedbacks->count() > 0) {
                // Calculate average across all rating fields
                $totalAverage = 0;
                $ratingFields = [
                    'teaching_effectiveness_rating',
                    'responsiveness_rating',
                    'explanation_clarity_rating',
                    'recommendation_rating'
                ];
                
                $fieldCount = 0;
                foreach ($ratingFields as $field) {
                    $avg = $implementorFeedbacks->avg($field);
                    if ($avg) {
                        $totalAverage += $avg;
                        $fieldCount++;
                    }
                }
                
                $this->overallRating = $fieldCount > 0 ? $totalAverage / $fieldCount : null;
            } else {
                $this->overallRating = null;
            }
        } else {
            $this->overallRating = null;
        }
    }

    public function render()
    {
        return view('livewire.implementors.implementor-dashboard');
    }
}