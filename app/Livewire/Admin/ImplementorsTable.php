<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class ImplementorsTable extends Component
{
    public $search = '';

    public function render()
    {
        $implementors = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('id', 2); // assuming role_id 2 = implementor
            })
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('username', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('username')
            ->get();

        return view('livewire.admin.implementors-table', [
            'implementors' => $implementors,
        ]);
    }
}
