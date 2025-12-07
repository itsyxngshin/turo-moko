<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; 
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Log; 
use App\Models\Setting;
use App\Models\Category;

#[Layout('layouts.layout-new')] 
class Settings extends Component
{
    use WithPagination; // Enable pagination methods

    public $activeTab = 'general';

    // Form Properties
    public $siteName = 'TURO-MOKO';
    public $maintenanceMode = false;
    public $allowRegistration = true;
    public $verifyImplementors = true;
    public $newCategoryName = '';

    public function mount()
    {
        // Load current state from DB
        $this->maintenanceMode = Setting::where('key', 'maintenance_mode')->value('value') == '1';
    }

    // Reset pagination when switching tabs (optional but good practice)
    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|min:3|max:50|unique:categories,category_name'
        ]);

        Category::create([
            'category_name' => $this->newCategoryName
        ]);

        $this->newCategoryName = ''; // Reset input
        session()->flash('message', 'Category added successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        
        if ($category) {
            $category->delete();
            session()->flash('message', 'Category deleted successfully.');
        }
    }

    public function saveGeneral()
    {
        // Save Maintenance Mode
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $this->maintenanceMode ? '1' : '0']
        );

        // [LOGGING] Good practice to log this major action
        \App\Models\Log::create([
            'user_id' => Auth::id(), 
            'action' => 'system.settings_update',
            'description' => 'Updated General Settings. Maintenance Mode: ' . ($this->maintenanceMode ? 'ON' : 'OFF'),
            'ip_address' => request()->ip()
        ]);

        session()->flash('message', 'General settings updated successfully.');
    }

    public function saveUsers()
    {
        // Save logic...
        session()->flash('message', 'User policies updated successfully.');
    }

    public function render()
    {
        $logs = [];
        $categories = [];

        // Fetch logs only if needed
        if ($this->activeTab === 'security') {
            $logs = Log::with('user.profile')->latest()->paginate(10);
        }
            
        // Fetch categories only if needed
        if ($this->activeTab === 'courses') {
            $categories = Category::orderBy('category_name')->get();
        }

        return view('livewire.admin.settings', [
            'logs' => $logs,
            'categories' => $categories
        ]);
    }
}