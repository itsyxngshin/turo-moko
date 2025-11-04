<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use App\Models\User;

class ViewImplementor extends Component
{
    public $showModal = false;
    public $implementor;

    protected $listeners = ['view-implementor' => 'loadImplementor'];

    public function loadImplementor($id)
    {
        // ✅ Make sure to load nested relation 'profile.photo'
        $this->implementor = User::with(['profile.photo', 'role'])->find($id);

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.admin.modal.view-implementor');
    }
}
