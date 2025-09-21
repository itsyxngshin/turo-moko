<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\CoverPhoto;
use App\Models\CourseTag;
use Illuminate\Support\Facades\Storage;

class EditCourse extends Component
{
    use WithFileUploads;

    public $courseId;
    public $name;
    public $background;
    public $category;
    public $thumbnail;           // new uploaded file
    public $categories = [];     // all categories
    public $existingThumbnail;   // path of current active photo
    public $course;
    public $tags = [];       // Array of existing tags
    public $tagInput = '';   // For new tag input
    public $start_date;      // Start date
    public $end_date;  

    public function mount($courseId)
    {
        $this->categories = \App\Models\Category::all();

        // Fetch the course with its active cover photo
        $this->course = Course::with('activeCoverPhoto')->findOrFail($courseId);

        $this->courseId   = $this->course->id;
        $this->name       = $this->course->name;
        $this->background = $this->course->background;
        $this->category   = $this->course->category_id;
      $this->start_date = $this->course->start_date
    ? \Carbon\Carbon::parse($this->course->start_date)->format('Y-m-d')
    : null;

$this->end_date = $this->course->end_date
    ? \Carbon\Carbon::parse($this->course->end_date)->format('Y-m-d')
    : null;

        $this->tags = $this->course->tags->pluck('tag')->toArray();
        $this->existingThumbnail = $this->course->activeCoverPhoto
            ? $this->course->activeCoverPhoto->path
            : null;
    }

    
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
    // Remove the tag from the local array (UI updates immediately)
    $this->tags = array_filter($this->tags, fn($t) => $t !== $tag);
}


    public function saveCourse()
{
    $this->validate([
        'name'       => 'required|string',
        'background' => 'required|string',
        'category'   => 'required|integer',
        'thumbnail'  => 'nullable|image|max:2048',
    ]);

    try {
        $course = Course::findOrFail($this->courseId);

        // Update course info
        $course->name        = $this->name;
        $course->background  = $this->background;
        $course->category_id = $this->category;
        $course->start_date  = $this->start_date;
        $course->end_date    = $this->end_date;
        $course->save();

        // Update tags
        CourseTag::where('course_id', $course->id)->delete(); // remove old tags
        foreach ($this->tags as $tagName) {
            CourseTag::create([
                'course_id' => $course->id,
                'tag' => $tagName,
            ]);
        }

        // Handle new cover photo
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('cover_photos', 'public');

            $coverPhoto = CoverPhoto::where('course_id', $course->id)
                                    ->where('status', 'Active')
                                    ->first();

            if ($coverPhoto) {
                if ($coverPhoto->path) {
                    Storage::disk('public')->delete($coverPhoto->path);
                }
                $coverPhoto->path = $path;
                $coverPhoto->save();
            } else {
                CoverPhoto::create([
                    'course_id' => $course->id,
                    'path'      => $path,
                    'status'    => 'Active',
                ]);
            }

            $this->existingThumbnail = $path;
            $this->thumbnail = null;
        }

        // Success alert
        $this->dispatch('swal', [
            'type' => 'success',
            'message' => 'Course updated successfully!',
        ]);

    } catch (\Exception $e) {
        // Error alert
        $this->dispatch('swal', [
            'type' => 'error',
            'message' => 'Failed to update course. ' . $e->getMessage(),
        ]);
    }
}




    public function render()
    {
        return view('livewire.modals.implementor.edit-course');
    }
}
