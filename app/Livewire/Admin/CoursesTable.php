<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class CoursesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingId = null;
    public $actionType = null;

    // Reset pagination when search term changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // 1. TRIGGER: Opens the modal
    public function confirmAction($action, $id)
    {
        $this->confirmingId = $id;
        $this->actionType = $action;
    }

    // 2. EXECUTE: Performs the action after user clicks "Yes" in modal
    public function executeAction()
    {
        if (!$this->confirmingId) return;

        // Call the specific logic based on action type
        switch ($this->actionType) {
            case 'archive':
                $this->archiveCourse($this->confirmingId);
                break;
            case 'unarchive':
                $this->unarchiveCourse($this->confirmingId);
                break;
            case 'delete':
                $this->deleteCourse($this->confirmingId);
                break;
            case 'restore':
                $this->restoreCourse($this->confirmingId);
                break;
        }

        // Close Modal
        $this->reset(['confirmingId', 'actionType']);
    }

    // 3. CANCEL: Closes modal without doing anything
    public function cancelAction()
    {
        $this->reset(['confirmingId', 'actionType']);
    }

    // 1. ARCHIVE: Hides course from students
    public function archiveCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'status' => 'archived', 
                'visibility' => 'hidden'
            ]);
            session()->flash('message', 'Course archived. It is now hidden.');
        }
    }

    // 2. UNARCHIVE: Makes course active and visible again
    public function unarchiveCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'status' => 'active', 
                'visibility' => 'visible'
            ]);
            session()->flash('message', 'Course successfully unarchived and is now visible.');
        }
    }

    // 3. DELETE (Soft): Sets status to 'deleted', keeps record in DB
    public function deleteCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'status' => 'deleted', 
                'visibility' => 'hidden'
            ]);
            session()->flash('message', 'Course moved to trash.');
        }
    }

    // 4. RESTORE: Recovers a deleted course to active status
    public function restoreCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'status' => 'active', 
                'visibility' => 'visible'
            ]);
            session()->flash('message', 'Course restored successfully.');
        }
    }

    public function render()
    {
        $courses = Course::query()
            ->with(['implementer.profile', 'category']) // Optimize queries
            ->where(function ($query) {
                $query->where('course_title', 'like', '%' . $this->search . '%')
                      ->orWhere('course_code', 'like', '%' . $this->search . '%');
            })
            // Optional: Sort so "Deleted" items might go to the bottom, or just standard date sort
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('livewire.admin.courses-table', [
            'courses' => $courses,
        ]);
    }
}