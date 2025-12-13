<?php

namespace App\Livewire\Implementor;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissions extends Component
{
    public $courseId;
    public $course;
    public $assignments;
    public $selectedAssignmentId;
    public $submissions = [];
    
    // Modal properties
    public $showGradeModal = false;
    public $selectedSubmission;
    public $studentName;
    public $submissionDate;
    public $grade;
    public $submissionId;
    
    public function mount()
    {
        $this->courseId = request()->get('course_id');
        
        if ($this->courseId) {
            $this->course = Course::findOrFail($this->courseId);
            
            // Load assignments for this course
            $this->assignments = Assignment::where('course_id', $this->courseId)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function updatedSelectedAssignmentId($value)
    {
        if ($value) {
            $this->loadSubmissions();
        } else {
            $this->submissions = [];
        }
    }

    private function loadSubmissions()
    {
        if (!$this->selectedAssignmentId) {
            $this->submissions = [];
            return;
        }

        // Get all enrolled students for this course
        $enrollees = CourseEnrollee::where('course_id', $this->courseId)
            ->whereIn('status', ['Active', 'Completed'])
            ->with(['user.profile'])
            ->get();

        $this->submissions = $enrollees->map(function($enrollee) {
            $submission = Submission::where('assignment_id', $this->selectedAssignmentId)
                ->where('enrollee_id', $enrollee->id)
                ->first();

            // Get student name from profile
            $studentName = 'Unknown';
            if ($enrollee->user) {
                $profile = $enrollee->user->profile;
                if ($profile) {
                    $studentName = trim($profile->first_name . ' ' . $profile->last_name);
                } else {
                    $studentName = $enrollee->user->username ?? $enrollee->user->email ?? 'Unknown';
                }
            }

            return [
                'id' => $submission?->id,
                'enrollee_id' => $enrollee->id,
                'student_name' => $studentName,
                'student_email' => $enrollee->user->email ?? '',
                'submitted_at' => $submission?->created_at,
                'status' => $submission 
                    ? ($submission->grade !== null ? 'Graded' : 'Submitted') 
                    : 'Not Submitted',
                'grade' => $submission?->grade ?? '-',
                'online_text' => $submission?->instruction,
                'file_path' => $submission?->attachment,
                'has_submission' => $submission !== null,
            ];
        })->toArray();
    }

    public function openGradeModal($submissionId)
    {
        if (!$submissionId) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'No submission found for this student.'
            ]);
            return;
        }

        $submission = Submission::with(['enrollee.user.profile', 'assignment'])
            ->findOrFail($submissionId);

        $this->submissionId = $submissionId;
        $this->selectedSubmission = [
            'id' => $submission->id,
            'online_text' => $submission->instruction,
            'file_path' => $submission->attachment,
            'file_url' => $submission->attachment ? Storage::url($submission->attachment) : null,
            'file_name' => $submission->attachment_original_name ?? ($submission->attachment ? basename($submission->attachment) : null),
            'assignment_title' => $submission->assignment->title ?? 'Assignment',
        ];

        // Get student name - match the loadSubmissions logic
        $studentName = 'Unknown Student';
        if ($submission->enrollee && $submission->enrollee->user) {
            $user = $submission->enrollee->user;
            $profile = $user->profile;
            if ($profile) {
                $studentName = trim($profile->first_name . ' ' . $profile->last_name);
            } else {
                $studentName = $user->username ?? $user->email ?? 'Unknown Student';
            }
        }
        
        $this->studentName = $studentName;
        $this->submissionDate = $submission->created_at->format('M d, Y h:i A');
        $this->grade = $submission->grade;

        $this->showGradeModal = true;
    }

    public function closeGradeModal()
    {
        $this->showGradeModal = false;
        $this->reset(['selectedSubmission', 'studentName', 'submissionDate', 'grade', 'submissionId']);
    }

    public function saveGrade()
    {
        $this->validate([
            'grade' => 'required|numeric|min:0|max:100',
        ]);

        $submission = Submission::findOrFail($this->submissionId);
        
        $submission->update([
            'grade' => $this->grade,
            'graded_by' => auth()->id(),
            'graded_at' => now(),
        ]);

        // Update local state
        $this->updateSubmissionInList($this->submissionId, $this->grade);

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Grade saved successfully!'
        ]);
        
        $this->closeGradeModal();
    }

    private function updateSubmissionInList($submissionId, $grade)
    {
        foreach ($this->submissions as &$submission) {
            if ($submission['id'] === $submissionId) {
                $submission['grade'] = $grade;
                $submission['status'] = 'Graded';
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.implementor.assignment-submissions')
            ->extends('layouts.layout')
            ->section('content');
    }
}
