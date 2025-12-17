<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\SectionHeader;

class AddSectionHeader extends Component
{
    public $courseId;
    public $title;

    protected $listeners = ['refreshSectionHeaderList' => '$refresh'];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
            ]);

            // Get the next order number across ALL timeline items
            $maxOrder = max(
                \App\Models\Module::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Assignment::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Quiz::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\ProgramEvaluation::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Announcement::where('course_id', $this->courseId)->max('order') ?? 0,
                SectionHeader::where('course_id', $this->courseId)->max('order') ?? 0
            );

            // Create the section header
            SectionHeader::create([
                'course_id' => $this->courseId,
                'title'     => $this->title,
                'order'     => $maxOrder + 1,
            ]);

            $this->resetForm();

            $this->dispatch('section-header-modal-close');

            $this->dispatch('swal:section-header-added', [
                'title' => 'Success!',
                'text'  => 'Section Header added successfully!',
            ]);

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['title']);
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-section-header');
    }
}
