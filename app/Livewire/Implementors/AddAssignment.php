<?php 

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Assignment;
use Carbon\Carbon;

class AddAssignment extends Component
{
    use WithFileUploads;

    public $courseId;
    public $course;
    public $assignmentName, $description, $attachment;
    public $enableDueDate = false;
    public $dueDay;
    public $dueMonth;
    public $dueYear;
    public $dueTime;
    public $submissionTypes = [];
    public $maxSize = '1 mb';

    public function mount(Course $course)
    {
        // Authorization check
        $implementor = auth()->user();
        
        if (!$implementor || $implementor->role_id !== 2) {
            abort(403, 'Unauthorized. You must be an implementor.');
        }

        // Verify the course belongs to the implementor
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'This course does not belong to you.');
        }

        $this->course = $course;
        $this->courseId = $course->id;
        
        // Initialize due date fields with default values
        $this->dueDay = 1;
        $this->dueMonth = 'Jan';
        $this->dueYear = now()->year;
        $this->dueTime = '09:00';
    }

    public function saveAssignment()
    {
        logger('=== Saving Assignment ===');
        logger('Enable Due Date: ' . ($this->enableDueDate ? 'true' : 'false'));
        logger('Due Day: ' . ($this->dueDay ?? 'null'));
        logger('Due Month: ' . ($this->dueMonth ?? 'null'));
        logger('Due Year: ' . ($this->dueYear ?? 'null'));
        logger('Due Time: ' . ($this->dueTime ?? 'null'));
        
        try {
            $this->validate([
                'assignmentName' => 'required|string|max:255',
                'description' => 'nullable|string',
                'attachment' => 'nullable|file|max:10240', // 10MB max
                'dueDay' => 'nullable|required_if:enableDueDate,true|integer|min:1|max:31',
                'dueMonth' => 'nullable|required_if:enableDueDate,true',
                'dueYear' => 'nullable|required_if:enableDueDate,true|integer',
                'dueTime' => 'nullable|required_if:enableDueDate,true',
            ]);
            logger('Validation passed');
        } catch (\Exception $e) {
            logger('Validation error: ' . $e->getMessage());
            throw $e;
        }

        // Convert month name to number
        $monthMap = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
            'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
        ];
        $monthNumber = $monthMap[$this->dueMonth] ?? '01';

        // Build end_date if due date is enabled
        $endDate = null;
        if ($this->enableDueDate && $this->dueDay && $this->dueMonth && $this->dueYear && $this->dueTime) {
            $endDate = Carbon::create(
                $this->dueYear,
                $monthNumber,
                $this->dueDay
            )->setTimeFromTimeString($this->dueTime);
        }

        // Handle file upload (if attachment column exists in future)
        // For now, attachment handling is skipped as the table doesn't have an attachment column
        // TODO: Add attachment column to assignments table or create assignment_attachments table

        // Map submission types to filetype_allowed boolean
        $filetypeAllowed = in_array('file', $this->submissionTypes);

        Assignment::create([
            'course_id' => $this->course->id,
            'lesson_id' => null, // Course-level assignment, not linked to lesson
            'title' => $this->assignmentName,
            'instruction' => $this->description ?? '',
            'status' => 'Open',
            'start_date' => Carbon::now(),
            'end_date' => $endDate,
            'filetype_allowed' => $filetypeAllowed,
            'order' => null,
            'visibility' => 'Active',
            'post_date' => Carbon::now(),
        ]);

        session()->flash('success', 'Assignment added successfully.');

        return redirect()->route('implementor.course-information', $this->course->course_code);
    }

    public function render()
    {
        return view('livewire.implementors.add-assignment')
            ->extends('layouts.layout')
            ->section('content');
    }
}
