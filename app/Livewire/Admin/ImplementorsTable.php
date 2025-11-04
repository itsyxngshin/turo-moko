<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ImplementorsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'username';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'tailwind';

    // Reset page when search input changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Export all implementors as CSV
    public function downloadCSV()
    {
        $implementors = User::with('role', 'profile')
            ->whereHas('role', fn($q) => $q->where('id', 2))
            ->get();

        $filename = 'implementors.csv';
        $path = storage_path("app/public/{$filename}");
        $handle = fopen($path, 'w+');

        fputcsv($handle, ['ID', 'Name', 'Username', 'Email', 'Phone', 'Status']);

        foreach ($implementors as $user) {
            fputcsv($handle, [
                $user->id,
                trim(($user->profile->first_name ?? '') . ' ' . ($user->profile->last_name ?? '')),
                $user->username,
                $user->email,
                $user->phonenum ?? '—',
                $user->profile->status ?? 'Active',
            ]);
        }

        fclose($handle);

        return response()->download($path)->deleteFileAfterSend();
    }

    // Trigger print action handled by Alpine
    
    public function printTable()
    {
        $this->dispatch('print-table');
    }

   
        public function render()
    {
        $implementors = User::with('profile', 'role')
            ->whereHas('role', fn($q) => $q->where('id', 2))
            ->when($this->search, function ($query) {
                $search = "%{$this->search}%";
                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhereHas('profile', function ($q) use ($search) {
                            $q->where('first_name', 'like', $search)
                              ->orWhere('last_name', 'like', $search)
                              ->orWhere('middle_name', 'like', $search);
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.implementors-table', compact('implementors'));
    }
}
