<?php

namespace App\Livewire\Inputs;

use Livewire\Component;

class Password extends Component
{
    public $value = '';
    public $placeholder = 'Enter your password here';

    public function updatedValue($val)
    {
        // Emit value back to parent component
        $this->dispatch('password-updated', $val);
    }
    
    public function render()
    {
        return view('livewire.inputs.password');
    }
}
