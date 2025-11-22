<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\CoverPhoto;
use App\Models\CourseTag;
use Illuminate\Support\Facades\Storage;

class ModifyCourse extends Component
{
    use WithFileUploads;

    public $courseId;
    public $name;
    public $background;
    public $category;
    public $visibility;         // Added visibility
    public $thumbnail;           
    public $categories = [];    
    public $existingThumbnail;  
    public $course;
    public $tags = [];          
    public $tagInput = '';      
    public $start_date;         
    public $end_date;  
    public $student_limit;

    public function mount($courseId)
    {
        $this->categories = \App\Models\Category::all();

        $this->course = Course::with('activeCoverPhoto', 'implementer.profile' )->findOrFail($courseId);

        $this->courseId        = $this->course->id;
        $this->name            = $this->course->name;
        $this->background      = $this->course->background;
        $this->category        = $this->course->category_id;
        $this->visibility      = $this->course->visibility ?? 'public'; // Set default if null
        $this->student_limit   = $this->course->student_limit;
        $this->start_date      = $this->course->start_date
                                    ? \Carbon\Carbon::parse($this->course->start_date)->format('Y-m-d')
                                    : null;
        $this->end_date        = $this->course->end_date
                                    ? \Carbon\Carbon::parse($this->course->end_date)->format('Y-m-d')
                                    : null;

        $this->tags            = $this->course->tags->pluck('tag')->toArray();
        $this->existingThumbnail = $this->course->activeCoverPhoto
            ? Storage::url($this->course->activeCoverPhoto->path)
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
    $this->tags = array_filter($this->tags, fn($t) => $t !== $tag);
}

    public function saveCourse()
    {
        $this->validate([
            'name'           => 'required|string',
            'background'     => 'required|string',
            'category'       => 'required|integer',
            'visibility'     => 'required|in:public,private', // Validate visibility
            'thumbnail'      => 'nullable|image|max:2048',
            'student_limit'  => 'required|integer|min:1|max:20',
        ]);

        try {
            $course = Course::findOrFail($this->courseId);

            // Update course info including visibility
            $course->update([
                'name'         => $this->name,
                'background'   => $this->background,
                'category_id'  => $this->category,
                'visibility'   => $this->visibility, // Save visibility
                'start_date'   => $this->start_date,
                'end_date'     => $this->end_date,
                'student_limit'=> $this->student_limit,
            ]);

            // Update tags
            CourseTag::where('course_id', $course->id)->delete();
            foreach ($this->tags as $tagName) {
                CourseTag::create([
                    'course_id' => $course->id,
                    'tag'       => $tagName,
                ]);
            }

            // Handle cover photo
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

            $this->dispatch('swal', [
                'type' => 'success',
                'message' => 'Course updated successfully!',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'message' => 'Failed to update course. ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.modal.modify-course');
    }
}
