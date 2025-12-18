<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\SectionHeader;

class EditSectionHeader extends Component
{
    public $sectionHeaderId;
    public $title;

    public function mount($sectionHeaderId)
    {
        $this->sectionHeaderId = $sectionHeaderId;
        $sectionHeader = SectionHeader::findOrFail($sectionHeaderId);
        $this->title = $sectionHeader->title;
    }

    public function update()
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
            ]);

            $sectionHeader = SectionHeader::findOrFail($this->sectionHeaderId);
            $sectionHeader->update([
                'title' => $this->title,
            ]);

            $this->dispatch('edit-section-header-modal-close');

            $this->dispatch('swal:section-header-updated', [
                'title' => 'Success!',
                'text'  => 'Section Header updated successfully!',
            ]);

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $sectionHeader = SectionHeader::findOrFail($this->sectionHeaderId);
            $sectionHeader->delete();

            $this->dispatch('edit-section-header-modal-close');

            $this->dispatch('swal:section-header-deleted', [
                'title' => 'Deleted!',
                'text'  => 'Section Header deleted successfully!',
            ]);

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.modals.implementor.edit-section-header');
    }
}
