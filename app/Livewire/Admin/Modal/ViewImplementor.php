<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\User;

class ViewImplementor extends Component
{
    public $showModal = false;
    public $implementor;

    #[On('view-implementor')] 
    public function loadImplementor($id)
    {
        // Load relationships including role
        $this->implementor = User::with(['profile.photo', 'role'])->find($id);

        if ($this->implementor) {
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->implementor = null; // Clear data
    }

    public function render()
    {
        return view('livewire.admin.modal.view-implementor');
    }
}