<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Category;
use App\Models\Thumbnail;
use Illuminate\Support\Facades\Auth;

class CreateCourse extends Component
{
    use WithFileUploads;

    // Form fields
    public $name;
    public $subject;
    public $background;
    public $status = 'draft'; // default
    public $visibility = 'private'; // default
    public $start_date;
    public $end_date;
    public $category_id;
    public $thumbnail;
    public $categories = [];
    public $tagInput = '';
    public $tags = []; // holds tags for this course

    public function addTag()
    {
        $clean = strtolower(trim($this->tagInput));
        $clean = preg_replace('/\s+/', '', $clean); // no spaces inside a single tag

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
        // Load categories for the dropdown
        $this->categories = Category::all();
    }

    public function saveCourse()
    {
        $this->validate([
        'name'        => 'required|string|max:255',
        'background'  => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'thumbnail'   => 'nullable|image|max:2048',
    ]);

        $coverPhotoId = null;

        // Handle file upload if exists
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('covers', 'public');

            $thumbnail = Thumbnail::create([
                'filename'    => $this->thumbnail->getClientOriginalName(),
                'path'        => $path,
                'mime_type'   => $this->thumbnail->getMimeType(),
                'uploaded_by' => Auth::id(), // if you kept uploader
            ]);

            $coverPhotoId = $thumbnail->id;
        }

        // Hardcode implementer_id = 2 for now
        $course = Course::create([
        'implementer_id' => 2,
        'organization_id' => 1,
        'category_id' => $this->category_id,
        'name' => $this->name,
        'background' => $this->background,
        'status' => $this->status,
        'visibility' => $this->visibility,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'cover_photo_id' => $coverPhotoId ?? null,
    ]);

     // Save tags into `course_tags`
    foreach ($this->tags as $tag) {
        CourseTag::create([
            'course_id' => $course->id,
            'tag' => $tag,
        ]);
    }

        // Reset form
        $this->reset([
            'name', 'subject', 'background', 'category_id',
            'thumbnail', 'tags', 'start_date', 'end_date'
        ]);

        // Close modal via Alpine.js
        $this->dispatch('course-saved');

        session()->flash('message', 'Course created successfully!');
    }

    public function render()
    {
        return view('livewire.modals.implementor.create-course');
    }
}
