<?php 

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Assignment;

class AddAssignment extends Component
{
    use WithFileUploads;

    public $courseId;
    public $course;
    public $assignmentName, $description, $attachment;
    public $enableDueDate = false;
    public $dueDay, $dueMonth, $dueYear, $dueTime;
    public $submissionTypes = [];
    public $maxSize = '1 mb';

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::findOrFail($courseId);
    }

    public function show($courseId)
{
    $course = Course::findOrFail($courseId);

    return view('livewire.implementors.implementor-course-details', [
        'course' => $course, 
        'courseId' => $course->id,
    ]);
}

    public function saveAssignment()
    {
        $this->validate([
            'assignmentName' => 'required|string|max:255',
        ]);

        Assignment::create([
            'course_id' => $this->courseId,
            'name' => $this->assignmentName,
            'description' => $this->description,
            'due_date' => $this->enableDueDate 
                ? "{$this->dueYear}-{$this->dueMonth}-{$this->dueDay} {$this->dueTime}" 
                : null,
            'submission_types' => json_encode($this->submissionTypes),
            'max_size' => $this->maxSize,
        ]);

        $this->dispatchBrowserEvent('swal:success', [
            'title' => 'Assignment Added',
            'text' => 'The assignment has been successfully created.',
        ]);

       
        return redirect()->route('implementors.course-info', $this->courseId);
    }

    public function render()
    {
        return view('livewire.implementors.add-assignment');
    }
}
