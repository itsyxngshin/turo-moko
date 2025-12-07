<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Assignment;
use Carbon\Carbon;

class EditAssignment extends Component
{
    use WithFileUploads;

    public $course;
    public $assignment;

    public $assignmentName;
    public $description;
    public $attachment;
    public $enableDueDate = false;
    public $dueDay;
    public $dueMonth;
    public $dueYear;
    public $dueTime;
    public $submissionTypes = [];
    public $maxSize = '1 mb';

    public function mount(Course $course, Assignment $assignment)
    {
        $implementor = auth()->user();
        if (!$implementor || $implementor->role_id !== 2 || $course->implementer_id !== $implementor->id || $assignment->course_id !== $course->id) {
            abort(403, 'Unauthorized.');
        }

        $this->course = $course;
        $this->assignment = $assignment;

        $this->assignmentName = $assignment->title;
        $this->description = $assignment->instruction;
        $this->enableDueDate = !is_null($assignment->end_date);

        $endDate = $assignment->end_date ? Carbon::parse($assignment->end_date) : null;
        if ($endDate) {
            $this->dueDay = $endDate->day;
            $this->dueMonth = $endDate->format('M');
            $this->dueYear = $endDate->year;
            $this->dueTime = $endDate->format('H:i');
        } else {
            $this->dueDay = 1;
            $this->dueMonth = 'Jan';
            $this->dueYear = now()->year;
            $this->dueTime = '09:00';
        }

        $this->submissionTypes = $assignment->filetype_allowed ? ['file'] : ['text'];
    }

    public function updateAssignment()
    {
        $this->validate([
            'assignmentName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
            'dueDay' => 'nullable|required_if:enableDueDate,true|integer|min:1|max:31',
            'dueMonth' => 'nullable|required_if:enableDueDate,true',
            'dueYear' => 'nullable|required_if:enableDueDate,true|integer',
            'dueTime' => 'nullable|required_if:enableDueDate,true',
        ]);

        $monthMap = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
            'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
        ];
        $monthNumber = $monthMap[$this->dueMonth] ?? '01';

        $endDate = null;
        if ($this->enableDueDate && $this->dueDay && $this->dueMonth && $this->dueYear && $this->dueTime) {
            $endDate = Carbon::create(
                $this->dueYear,
                $monthNumber,
                $this->dueDay
            )->setTimeFromTimeString($this->dueTime);
        }

        $filetypeAllowed = in_array('file', $this->submissionTypes);

        $this->assignment->update([
            'title' => $this->assignmentName,
            'instruction' => $this->description ?? '',
            'end_date' => $endDate,
            'filetype_allowed' => $filetypeAllowed,
        ]);

        session()->flash('success', 'Assignment updated successfully.');

        return redirect()->route('implementor.course-information', $this->course->course_code);
    }

    public function render()
    {
        return view('livewire.implementors.edit-assignment')
            ->extends('layouts.layout')
            ->section('content');
    }
}

