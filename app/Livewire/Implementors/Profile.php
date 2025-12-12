<?php

namespace App\Livewire\Implementors;

use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Photo; 
use App\Models\WorkPortfolio;
use App\Models\Engagement;
use App\Models\Log; 

class Profile extends Component
{
    use WithFileUploads; 

    public $user;
    public $active_engagement;
    
    // UI Properties
    public $showEditModal = false;
    
    // Form Properties
    public $first_name;
    public $last_name;
    public $middle_name;
    public $username;
    public $email;
    public $new_photo;
    
    // Dynamic Lists for Form
    public $work_experiences = [];
    public $engagements_list = []; 

    // Search & Sort
    public $search = '';
    public $sort = 'latest'; 

    // Display Data (Calculated in Render)
    public $activeCoursesCount = 0; 

    public function mount()
    {
        if (!Auth::check()) return redirect()->route('login');

        // 1. Load User & Relationships
        $this->user = User::with([
            'profile.portfolioSets.workPortfolio', 
            'engagements'
        ])->find(Auth::id());
        
        $this->active_engagement = $this->user->engagements->last();

        // 2. Initialize Form Data
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;

        // 3. Load Edit Lists
        $this->loadWorkExperiences();
        $this->engagements_list = $this->user->engagements->toArray();
    }

    public function loadWorkExperiences()
    {
        $this->work_experiences = [];
        if ($this->user->profile->portfolioSets) {
            foreach ($this->user->profile->portfolioSets as $set) {
                if ($set->workPortfolio) {
                    $this->work_experiences[] = [
                        'portfolio_set_id' => $set->id, 
                        'work_portfolio_id' => $set->work_portfolio_id, 
                        'designation' => $set->workPortfolio->designation,
                        'workplace' => $set->workPortfolio->workplace,
                        'duration' => $set->workPortfolio->duration,
                        'status' => $set->workPortfolio->status,
                        'description' => $set->workPortfolio->description,
                    ];
                }
            }
        }
    }

    public function openEditModal()
    {
        // Re-sync data just in case
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->loadWorkExperiences();
        $this->engagements_list = $this->user->engagements->toArray();
        $this->showEditModal = true;
    }

    public function addWorkExperience()
    {
        $this->work_experiences[] = [
            'portfolio_set_id' => null, 
            'work_portfolio_id' => null,
            'designation' => '',
            'workplace' => '', 
            'duration' => '',
            'status' => 'Active',
            'description' => ''
        ];
    }

    public function removeWorkExperience($index)
    {
        $item = $this->work_experiences[$index];
        if (!empty($item['portfolio_set_id'])) {
            $set = \App\Models\PortfolioSet::find($item['portfolio_set_id']);
            if ($set) {
                if ($set->work_portfolio_id) {
                    \App\Models\WorkPortfolio::find($set->work_portfolio_id)?->delete();
                }
                $set->delete();
            }
        }
        unset($this->work_experiences[$index]);
        $this->work_experiences = array_values($this->work_experiences);
    }

    public function addEngagement()
    {
        $this->engagements_list[] = ['id' => null, 'title' => '', 'description' => ''];
    }

    public function removeEngagement($index)
    {
        $item = $this->engagements_list[$index];
        if (!empty($item['id'])) {
            \App\Models\Engagement::find($item['id'])?->delete();
        }
        unset($this->engagements_list[$index]);
        $this->engagements_list = array_values($this->engagements_list);
    }

    public function saveProfile()
    {
        // 1. Update Profile
        $this->user->profile->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
        ]);

        // 2. Update Username
        if ($this->user->username !== $this->username) {
            $oldUser = $this->user->username;
            $this->user->update(['username' => $this->username]);
            $this->logActivity('Updated', "Changed username from {$oldUser} to {$this->username}");
        }

        // 3. Update Photo
        if ($this->new_photo) {
            $path = $this->new_photo->store('photos', 'public');
            $photoRecord = Photo::create(['photos' => $path]);
            $this->user->profile->update(['photo_id' => $photoRecord->id]);
            $this->logActivity('Updated', "Updated profile picture");
        }

        // 4. Update Work Experiences
        foreach ($this->work_experiences as $work) {
            if (!empty($work['work_portfolio_id'])) {
                \App\Models\WorkPortfolio::find($work['work_portfolio_id'])->update($work);
            } else {
                $new = \App\Models\WorkPortfolio::create($work);
                \App\Models\PortfolioSet::create([
                    'profile_id' => $this->user->profile->id,
                    'work_portfolio_id' => $new->id,
                ]);
            }
        }

        // 5. Update Engagements
        foreach ($this->engagements_list as $eng) {
            if(empty($eng['title'])) continue; 
            if (isset($eng['id']) && $eng['id']) {
                \App\Models\Engagement::find($eng['id'])->update(['title' => $eng['title'], 'description' => $eng['description']]);
            } else {
                $this->user->engagements()->create(['title' => $eng['title'], 'description' => $eng['description']]);
            }
        }

        $this->user->refresh();
        $this->showEditModal = false;
        $this->new_photo = null; 
        session()->flash('message', 'Profile updated successfully!');
    }

    // Helper for Logging
    private function logActivity($action, $description, $model = null)
    {
        Log::create([
            'user_id' => Auth::id(),
            'action' => $action, 
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'loggable_type' => $model ? get_class($model) : null,
            'loggable_id' => $model ? $model->id : null,
        ]);
    }

    // Reset pagination/list on search
    public function updatedSearch() { /* handled in render */ }
    public function updatedSort() { /* handled in render */ }

    public function render()
    {
        // -----------------------------------------------------------
        // FETCH ACTIVE COURSES (Implementor = Teacher)
        // -----------------------------------------------------------
        $query = Course::query()
            ->where('implementer_id', $this->user->id)
            ->where('status', 'Active')
            ->with(['activeCoverPhoto', 'category']);

        // Search Logic
        if ($this->search) {
            $query->where(function($q) {
                $q->where('course_title', 'like', '%'.$this->search.'%')
                  ->orWhere('background', 'like', '%'.$this->search.'%')
                  ->orWhereHas('category', fn($sq) => $sq->where('category_name', 'like', '%'.$this->search.'%'));
            });
        }

        // Sort Logic
        switch ($this->sort) {
            case 'oldest': $query->oldest('created_at'); break;
            case 'a-z':    $query->orderBy('course_title', 'asc'); break;
            case 'z-a':    $query->orderBy('course_title', 'desc'); break;
            default:       $query->latest('created_at'); break;
        }

        $activeCourses = $query->get();
        $this->activeCoursesCount = $activeCourses->count();

        return view('livewire.implementors.profile', [
            'activeCourses' => $activeCourses,
            'user' => $this->user
        ])->layout('layouts.layout');
    }
}