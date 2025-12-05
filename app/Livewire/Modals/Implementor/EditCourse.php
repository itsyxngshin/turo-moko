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
    public $course_title;
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
    public $student_limit;
    public $visibility; // 'public' or 'private'


    public function mount($courseId)
    {
        $this->categories = \App\Models\Category::all();

        // Fetch the course with its active cover photo
        $this->course = Course::with('activeCoverPhoto')->findOrFail($courseId);

        $this->courseId   = $this->course->id;
        $this->course_title = $this->course->course_title;
        $this->background = $this->course->background;
        $this->category   = $this->course->category_id;
        $this->student_limit   = $this->course->student_limit;
        $this->visibility = $this->course->visibility ?? 'public'; // default to public

      $this->start_date = $this->course->start_date
    ? \Carbon\Carbon::parse($this->course->start_date)->format('Y-m-d')
    : null;

$this->end_date = $this->course->end_date
    ? \Carbon\Carbon::parse($this->course->end_date)->format('Y-m-d')
    : null;

        $this->tags = $this->course->tags->pluck('tag')->toArray();
$this->existingThumbnail = $this->course->activeCoverPhoto
    ? asset('storage/' . $this->course->activeCoverPhoto->path)
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


public $removeExistingThumbnail = false;


  public function saveCourse()
{
    $this->validate([
        'course_title'          => 'required|string',
        'background'    => 'required|string',
        'category'      => 'required|integer',
        'visibility'    => 'required|in:visible,hidden',
        'thumbnail'     => 'nullable|image|max:2048',
        'student_limit' => 'required|integer|min:1|max:20',
    ]);

    try {
        $course = Course::findOrFail($this->courseId);

        // Update course info
        $course->update([
            'course_title'  => $this->course_title,
            'background'    => $this->background,
            'category_id'   => $this->category,
            'start_date'    => $this->start_date,
             'visibility'    => $this->visibility,
            'end_date'      => $this->end_date,
            'student_limit' => $this->student_limit,
        ]);

        // Update tags
        CourseTag::where('course_id', $course->id)->delete();
        foreach ($this->tags as $tagName) {
            CourseTag::create([
                'course_id' => $course->id,
                'tag'       => $tagName,
            ]);
        }

        // Replace existing cover photo if new file is uploaded
        // Handle cover photo upload
if ($this->thumbnail) {
    // Store the new uploaded file in 'public/cover_photos'
    $path = $this->thumbnail->store('cover_photos', 'public');

    // Check if the course already has an active cover photo
    $coverPhoto = CoverPhoto::where('course_id', $course->id)
                            ->where('status', 'Active')
                            ->first();

    if ($coverPhoto) {
        // Delete old file from storage
        if ($coverPhoto->path) {
            Storage::disk('public')->delete($coverPhoto->path);
        }
        // Update the existing record with the new path
        $coverPhoto->path = $path;
        $coverPhoto->save();
    } else {
        // No existing cover photo, so create a new record
        CoverPhoto::create([
            'course_id' => $course->id,
            'path'      => $path,
            'status'    => 'Active',
        ]);
    }

    // Update Livewire property so Alpine can show preview
    $this->existingThumbnail = $path;
    $this->thumbnail = null;
}


        $this->dispatch('swal', [
            'type'    => 'success',
            'message' => 'Course updated successfully!',
        ]);

    } catch (\Exception $e) {
        $this->dispatch('swal', [
            'type'    => 'error',
            'message' => 'Failed to update course. ' . $e->getMessage(),
        ]);
    }
}




    public function render()
    {
        return view('livewire.modals.implementor.edit-course');
    }
}
