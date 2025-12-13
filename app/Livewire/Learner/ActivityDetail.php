<?php

namespace App\Livewire\Learner;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ActivityDetail extends Component
{
    use WithFileUploads;

    // Allowed file types for LMS submissions
    const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'image/jpeg',
        'image/png',
        'image/jpg',
        'application/zip',
        'application/x-zip-compressed',
    ];

    const ALLOWED_EXTENSIONS = 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,zip';

    // Computed properties for Livewire
    protected function queryStringComputedPropertyCache()
    {
        return [];
    }

    public $course;
    public $assignment;
    public $submission;
    public $enrolleeRecord;
    
    // Form fields
    public $onlineText = '';
    public $fileUpload;
    
    // State management
    public $currentState = 'NOT_SUBMITTED'; // NOT_SUBMITTED, SUBMITTING, SUBMITTED

    public function mount(Course $course, Assignment $assignment)
    {
        $this->course = $course;
        $this->assignment = $assignment;
        
        // Check if learner is enrolled
        $learner = Auth::user();
        if (!$learner || (int) $learner->role_id !== 1) {
            abort(403, 'Unauthorized. You must be a learner.');
        }

        $isEnrolled = $course->enrollees()
            ->where('users.id', $learner->id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Get enrollee record
        $this->enrolleeRecord = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $learner->id)
            ->first();

        // Check for existing submission
        if ($this->enrolleeRecord) {
            $this->submission = Submission::where('assignment_id', $assignment->id)
                ->where('enrollee_id', $this->enrolleeRecord->id)
                ->first();

            if ($this->submission) {
                $this->currentState = 'SUBMITTED';
                $this->onlineText = $this->submission->instruction ?? '';
            }
        }
    }

    public function startSubmission()
    {
        if ($this->isPastDue) {
            session()->flash('error', 'This assignment is past the due date.');
            return;
        }
        $this->currentState = 'SUBMITTING';
    }

    public function cancelSubmission()
    {
        // Reset form fields
        $this->fileUpload = null;
        $this->onlineText = $this->submission->instruction ?? '';
        
        // Go back to appropriate state
        $this->currentState = $this->submission ? 'SUBMITTED' : 'NOT_SUBMITTED';
    }

    public function removeFile()
    {
        $this->fileUpload = null;
    }

    public function updatedFileUpload()
    {
        // Validate file size and type immediately when file is selected
        if ($this->fileUpload) {
            $maxSizeKB = $this->assignment->max_file_size;
            
            $this->validateOnly('fileUpload', [
                'fileUpload' => [
                    'required',
                    'file',
                    'max:' . $maxSizeKB,
                    'mimes:' . self::ALLOWED_EXTENSIONS,
                ],
            ], [
                'fileUpload.max' => 'File size must not exceed ' . $this->formatFileSize($maxSizeKB) . '. Your file is ' . $this->formatFileSize($this->fileUpload->getSize() / 1024) . '.',
                'fileUpload.mimes' => 'Invalid file type. Allowed types: PDF, Word, Excel, PowerPoint, Text, Images (JPG, PNG), and ZIP files.',
            ]);
        }
    }

    public function submitAssignment()
    {
        // Check if past due date
        if ($this->isPastDue) {
            $this->addError('submission', 'This assignment is past the due date. Submissions are no longer accepted.');
            return;
        }

        // Dynamic validation based on assignment type
        $maxSizeKB = $this->assignment->max_file_size;
        
        if ($this->assignment->filetype_allowed && $this->assignment->text_allowed) {
            // Both allowed - at least one required
            $this->validate([
                'fileUpload' => 'nullable|file|max:' . $maxSizeKB . '|mimes:' . self::ALLOWED_EXTENSIONS,
                'onlineText' => 'nullable|string|min:10',
            ], [
                'fileUpload.max' => 'File size must not exceed ' . $this->formatFileSize($maxSizeKB) . '.',
                'fileUpload.mimes' => 'Invalid file type. Allowed: PDF, Word, Excel, PowerPoint, Text, Images, ZIP.',
                'onlineText.min' => 'Text submission must be at least 10 characters.',
            ]);

            // Ensure at least one is provided
            if (!$this->fileUpload && !$this->onlineText) {
                $this->addError('submission', 'Please provide either a file upload or online text submission.');
                return;
            }
        } elseif ($this->assignment->filetype_allowed) {
            // File submission required
            $this->validate([
                'fileUpload' => 'required|file|max:' . $maxSizeKB . '|mimes:' . self::ALLOWED_EXTENSIONS,
            ], [
                'fileUpload.required' => 'Please upload a file for this assignment.',
                'fileUpload.max' => 'File size must not exceed ' . $this->formatFileSize($maxSizeKB) . '.',
                'fileUpload.mimes' => 'Invalid file type. Allowed: PDF, Word, Excel, PowerPoint, Text, Images (JPG, PNG), ZIP.',
            ]);
        } elseif ($this->assignment->text_allowed) {
            // Text submission required
            $this->validate([
                'onlineText' => 'required|string|min:10',
            ], [
                'onlineText.required' => 'Please enter your text submission.',
                'onlineText.min' => 'Text submission must be at least 10 characters.',
            ]);
        }

        $filePath = null;
        $originalName = null;

        // Handle file upload
        if ($this->fileUpload) {
            $originalName = $this->fileUpload->getClientOriginalName();
            $filePath = $this->fileUpload->store('submissions', 'public');
        }

        // Create or update submission
        $submissionData = [
            'enrollee_id' => $this->enrolleeRecord->id,
            'assignment_id' => $this->assignment->id,
            'instruction' => $this->onlineText,
            'attachment' => $filePath,
            'attachment_original_name' => $originalName,
            'post_date' => now(),
            'visibility' => true,
        ];

        if ($this->submission) {
            // Update existing submission
            $this->submission->update($submissionData);
            $this->submission->edit_date = now();
            $this->submission->save();
        } else {
            // Create new submission
            $this->submission = Submission::create($submissionData);
        }

        $this->currentState = 'SUBMITTED';
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'Assignment submitted successfully!'
        ]);
    }

    public function editSubmission()
    {
        if ($this->isPastDue) {
            session()->flash('error', 'This assignment is past the due date. You cannot edit your submission.');
            return;
        }
        $this->currentState = 'SUBMITTING';
    }

    public function removeSubmission()
    {
        if ($this->submission) {
            // Delete file if exists
            if ($this->submission->attachment) {
                Storage::disk('public')->delete($this->submission->attachment);
            }

            $this->submission->delete();
            $this->submission = null;
            $this->onlineText = '';
            $this->fileUpload = null;
            $this->currentState = 'NOT_SUBMITTED';
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Removed!',
                'text' => 'Submission removed successfully!'
            ]);
        }
    }

    private function formatFileSize($sizeKB)
    {
        if ($sizeKB >= 1024) {
            return round($sizeKB / 1024, 1) . ' MB';
        }
        return $sizeKB . ' KB';
    }

    public function getIsPastDueProperty()
    {
        if (!$this->assignment->end_date) {
            return false; // No due date, never past due
        }

        return Carbon::now()->isAfter(Carbon::parse($this->assignment->end_date));
    }

    public function getTimeRemainingProperty()
    {
        if (!$this->assignment->end_date) {
            return null;
        }

        $endDate = Carbon::parse($this->assignment->end_date);
        $now = Carbon::now();

        if ($this->submission) {
            // If submitted, calculate how early/late
            $submittedDate = Carbon::parse($this->submission->post_date);
            if ($submittedDate->lessThan($endDate)) {
                $diff = (int) $submittedDate->diffInDays($endDate);
                return "Assignment was submitted {$diff} " . ($diff === 1 ? 'day' : 'days') . " early";
            } else {
                $diff = (int) $endDate->diffInDays($submittedDate);
                return "Assignment was submitted {$diff} " . ($diff === 1 ? 'day' : 'days') . " late";
            }
        } else {
            // Not submitted yet
            if ($now->lessThan($endDate)) {
                $diff = (int) $now->diffInDays($endDate);
                return "Assignment is due in {$diff} " . ($diff === 1 ? 'day' : 'days');
            } else {
                $diff = (int) $endDate->diffInDays($now);
                return "Assignment was due {$diff} " . ($diff === 1 ? 'day' : 'days') . " ago";
            }
        }
    }

    public function getTimeRemainingColorProperty()
    {
        if (!$this->assignment->end_date) {
            return 'text-gray-600';
        }

        $endDate = Carbon::parse($this->assignment->end_date);
        $now = Carbon::now();

        if ($this->submission) {
            // If submitted, check if it was on time
            $submittedDate = Carbon::parse($this->submission->post_date);
            if ($submittedDate->lessThan($endDate)) {
                // Submitted early - GREEN
                return 'text-green-600';
            } else {
                // Submitted late - RED
                return 'text-red-600';
            }
        } else {
            // Not submitted yet
            if ($now->lessThan($endDate)) {
                // Still time - GREEN
                return 'text-green-600';
            } else {
                // Overdue - RED
                return 'text-red-600';
            }
        }
    }

    public function render()
    {
        return view('livewire.learner.activity-detail')
            ->layout('layouts.layout')
            ->title($this->assignment->title);
    }
}

