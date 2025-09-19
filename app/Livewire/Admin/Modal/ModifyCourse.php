<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;

class ModifyCourse extends Component
{
    public $show = false;
    
    public function render()
    {
        return view('livewire.admin.modal.modify-course');
    }
}
