<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Category;
use App\Models\CourseTag;
use App\Models\CoverPhoto;
use Illuminate\Support\Facades\Auth;

class CreateCourse extends Component
{
    use WithFileUploads;

    // Form fields
    public $name;
    public $subject;
    public $background;
    public $status = 'Active'; // default
    public $visibility = 'Visible'; // default
    public $start_date;
    public $end_date;
    public $category_id;
    public $thumbnail; // uploaded file
    public $categories = [];
    public $tagInput = '';
    public $tags = []; // holds tags for this course

    public function addTag()
    {
        $clean = strtolower(trim($this->tagInput));
        $clean = preg_replace('/\s+/', '', $clean); // remove spaces

        if ($clean && !in_array($clean, $this->tags)) {
            $this->tags[] = $clean;
        }

        $this->tagInput = ''; // reset input
    }

    public function removeTag($tag)
    {
        $this->tags = array_filter($this->tags, fn($t) => $t !== $tag);
    }

    public function mount()
    {
        // Load categories for dropdown
        $this->categories = Category::all();
    }

    public function saveCourse()
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'background'  => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|max:2048',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        // Create course first
        $course = Course::create([
            'implementer_id'   => Auth::id() ?? 2, // fallback for now
            'organization_id'  => 1, // adjust if dynamic
            'category_id'      => $this->category_id,
            'name'             => $this->name,
            'background'       => $this->background,
            'status'           => $this->status,
            'visibility'       => $this->visibility,
            'start_date'       => $this->start_date,
            'end_date'         => $this->end_date,
        ]);

        // Handle cover photo upload
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('cover_photos', 'public');

            $course->coverPhotos()->create([
                'path'   => $path,
                'status' => 'Active',
            ]);
        }

        // Save tags into course_tags
        foreach ($this->tags as $tag) {
            CourseTag::create([
                'course_id' => $course->id,
                'tag'       => $tag,
            ]);
        }

        // Reset form fields
        $this->reset([
            'name', 'subject', 'background', 'category_id',
            'thumbnail', 'tags', 'start_date', 'end_date'
        ]);

        // Close modal and notify
        $this->dispatch('course-saved');
        session()->flash('message', 'Course created successfully!');
    }

    public function render()
    {
        return view('livewire.modals.implementor.create-course');
    }
}
