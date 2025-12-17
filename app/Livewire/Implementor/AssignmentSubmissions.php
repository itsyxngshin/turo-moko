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
    public $sortBy = 'name'; // 'name' or 'date'
    
    // Modal properties
    public $showGradeModal = false;
    public $currentSubmissionIndex = -1;
    public $selectedSubmission;
    public $studentName;
    public $submissionDate;
    public $grade;
    public $submissionId;
    public $textFileContent = null;
    
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
                'grade' => $submission 
                    ? ($submission->grade ?? '-')
                    : 0, // No submission treated as 0
                'online_text' => $submission?->instruction,
                'file_path' => $submission?->attachment,
                'has_submission' => $submission !== null,
            ];
        })->toArray();
        
        // Sort submissions based on selected sort option
        $this->sortSubmissions();
    }
    
    public function toggleSort($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->sortSubmissions();
    }
    
    private function sortSubmissions()
    {
        // Separate into three groups: ungraded submissions, graded submissions, no submissions
        $ungraded = array_filter($this->submissions, fn($s) => $s['has_submission'] && $s['status'] !== 'Graded');
        $graded = array_filter($this->submissions, fn($s) => $s['has_submission'] && $s['status'] === 'Graded');
        $noSubmission = array_filter($this->submissions, fn($s) => !$s['has_submission']);
        
        if ($this->sortBy === 'name') {
            // Sort alphabetically by student name for all groups
            usort($ungraded, function($a, $b) {
                return strcasecmp($a['student_name'], $b['student_name']);
            });
            usort($graded, function($a, $b) {
                return strcasecmp($a['student_name'], $b['student_name']);
            });
            usort($noSubmission, function($a, $b) {
                return strcasecmp($a['student_name'], $b['student_name']);
            });
        } else {
            // Sort by submission date (most recent first)
            usort($ungraded, function($a, $b) {
                // Put submissions without dates at the end
                if (!$a['submitted_at'] && !$b['submitted_at']) return 0;
                if (!$a['submitted_at']) return 1;
                if (!$b['submitted_at']) return -1;
                
                return $b['submitted_at']->timestamp <=> $a['submitted_at']->timestamp;
            });
            usort($graded, function($a, $b) {
                // Put submissions without dates at the end
                if (!$a['submitted_at'] && !$b['submitted_at']) return 0;
                if (!$a['submitted_at']) return 1;
                if (!$b['submitted_at']) return -1;
                
                return $b['submitted_at']->timestamp <=> $a['submitted_at']->timestamp;
            });
            // For no submission, sort by name as fallback (no dates to sort by)
            usort($noSubmission, function($a, $b) {
                return strcasecmp($a['student_name'], $b['student_name']);
            });
        }
        
        // Combine: ungraded first, then graded, then no submission
        $this->submissions = array_merge(
            array_values($ungraded), 
            array_values($graded), 
            array_values($noSubmission)
        );
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
        
        // Find the index of this submission
        foreach ($this->submissions as $index => $sub) {
            if ($sub['id'] === $submissionId) {
                $this->currentSubmissionIndex = $index;
                break;
            }
        }

        $submission = Submission::with(['enrollee.user.profile', 'assignment'])
            ->findOrFail($submissionId);

        $this->submissionId = $submissionId;
        
        // Determine file type and preview capability
        $fileExtension = null;
        $fileType = null;
        $canPreview = false;
        
        if ($submission->attachment) {
            $fileExtension = strtolower(pathinfo($submission->attachment, PATHINFO_EXTENSION));
            
            // Categorize file types
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'])) {
                $fileType = 'image';
                $canPreview = true;
            } elseif ($fileExtension === 'pdf') {
                $fileType = 'pdf';
                $canPreview = true;
            } elseif (in_array($fileExtension, ['txt', 'md', 'csv'])) {
                $fileType = 'text';
                $canPreview = true;
            } elseif (in_array($fileExtension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
                $fileType = 'document';
                $canPreview = true; // Use Google Docs viewer
            } else {
                $fileType = 'other';
                $canPreview = false;
            }
        }
        
        // Load text file content if applicable
        $this->textFileContent = null;
        if ($submission->attachment && $fileType === 'text') {
            try {
                $fullPath = storage_path('app/public/' . $submission->attachment);
                if (file_exists($fullPath)) {
                    $this->textFileContent = file_get_contents($fullPath);
                }
            } catch (\Exception $e) {
                $this->textFileContent = 'Error loading file content.';
            }
        }
        
        $this->selectedSubmission = [
            'id' => $submission->id,
            'online_text' => $submission->instruction,
            'file_path' => $submission->attachment,
            'file_url' => $submission->attachment ? Storage::url($submission->attachment) : null,
            'file_name' => $submission->attachment_original_name ?? ($submission->attachment ? basename($submission->attachment) : null),
            'assignment_title' => $submission->assignment->title ?? 'Assignment',
            'file_extension' => $fileExtension,
            'file_type' => $fileType,
            'can_preview' => $canPreview,
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
        $this->reset(['selectedSubmission', 'studentName', 'submissionDate', 'grade', 'submissionId', 'textFileContent', 'currentSubmissionIndex']);
    }
    
    public function navigateToPrevious()
    {
        if ($this->currentSubmissionIndex > 0) {
            $prevIndex = $this->currentSubmissionIndex - 1;
            
            // Find previous submission that has content
            while ($prevIndex >= 0 && !$this->submissions[$prevIndex]['has_submission']) {
                $prevIndex--;
            }
            
            if ($prevIndex >= 0 && $this->submissions[$prevIndex]['has_submission']) {
                $this->openGradeModal($this->submissions[$prevIndex]['id']);
            }
        }
    }
    
    public function navigateToNext()
    {
        if ($this->currentSubmissionIndex < count($this->submissions) - 1) {
            $nextIndex = $this->currentSubmissionIndex + 1;
            
            // Find next submission that has content
            while ($nextIndex < count($this->submissions) && !$this->submissions[$nextIndex]['has_submission']) {
                $nextIndex++;
            }
            
            if ($nextIndex < count($this->submissions) && $this->submissions[$nextIndex]['has_submission']) {
                $this->openGradeModal($this->submissions[$nextIndex]['id']);
            }
        }
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
        
        // Re-sort submissions to reflect the new graded status
        $this->sortSubmissions();

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Grade saved successfully!'
        ]);
        
        // Auto-advance to next ungraded submission
        $nextUngradedIndex = $this->findNextUngradedSubmission();
        
        if ($nextUngradedIndex !== -1) {
            $this->openGradeModal($this->submissions[$nextUngradedIndex]['id']);
        } else {
            // No more ungraded submissions, close modal
            $this->closeGradeModal();
            $this->dispatch('show-alert', [
                'type' => 'info',
                'message' => 'All submissions have been graded!'
            ]);
        }
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
    
    private function findNextUngradedSubmission()
    {
        // After sorting, ungraded submissions are always at the top
        // Simply find the first ungraded submission from the beginning
        for ($i = 0; $i < count($this->submissions); $i++) {
            if ($this->submissions[$i]['has_submission'] && $this->submissions[$i]['status'] !== 'Graded') {
                return $i;
            }
        }
        
        return -1; // No ungraded submissions found
    }
    
    public function hasMoreUngradedSubmissions()
    {
        return $this->findNextUngradedSubmission() !== -1;
    }

    public function render()
    {
        return view('livewire.implementor.assignment-submissions')
            ->extends('layouts.layout')
            ->section('content');
    }
}
