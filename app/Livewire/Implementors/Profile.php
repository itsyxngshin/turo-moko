<?php

namespace App\Livewire\Implementors;

use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Photo; 
use App\Models\WorkPortfolio;
use App\Models\Engagement;
use App\Models\Log; 

class Profile extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $user;
    public $active_engagement;
    
    // Search & Sort properties
    public $search = '';
    public $sort = 'latest'; 
    public $showEditModal = false;
    
    // Form Properties
    public $first_name;
    public $last_name;
    public $middle_name; // Already here
    public $username;    // [NEW]
    public $email;       // [NEW]
    public $portfolio_link;
    public $new_photo;
    
    // DATA LISTS
    public $work_experiences = [];
    public $engagements_list = []; 

    public function mount()
    {
        if (!Auth::check()) return redirect()->route('login');

        $this->user = User::with([
            'profile.portfolioSets.workPortfolio', 
            'engagements'
        ])->find(Auth::id());
        
        $this->active_engagement = $this->user->engagements->last();
    }

    public function openEditModal()
    {
        // 1. Load Profile Data
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        
        // 2. Load User Account Data [NEW]
        $this->username = $this->user->username;
        $this->email = $this->user->email;

        // --- Flatten Work Experience for Form ---
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

        // --- Load Engagements ---
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
                    $portfolio = \App\Models\WorkPortfolio::find($set->work_portfolio_id);
                    
                    if ($portfolio) {
                        $this->logActivity('Deleted', "Removed work experience: {$portfolio->designation}", $portfolio);
                        $portfolio->delete();
                    }
                }
                $set->delete();
            }
        }
        
        unset($this->work_experiences[$index]);
        $this->work_experiences = array_values($this->work_experiences);
    }

    public function addEngagement()
    {
        $this->engagements_list[] = [
            'id' => null,
            'title' => '',
            'description' => '',
        ];
    }

    public function removeEngagement($index)
    {
        $item = $this->engagements_list[$index];

        if (!empty($item['id'])) {
            $engagement = \App\Models\Engagement::find($item['id']);
            
            if ($engagement) {
                $this->logActivity('Deleted', "Removed engagement: {$engagement->title}", $engagement);
                $engagement->delete();
            }
        }

        unset($this->engagements_list[$index]);
        $this->engagements_list = array_values($this->engagements_list);
    }

    public function saveProfile()
    {
        // 1. Update Profile (Names)
        $this->user->profile->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name, // Updated
            'last_name' => $this->last_name,
        ]);

        // 2. Update User Account (Username Only) [NEW]
        // We check if it changed to prevent unnecessary queries/logs
        if ($this->user->username !== $this->username) {
            $oldUsername = $this->user->username;
            $this->user->update([
                'username' => $this->username
            ]);
            $this->logActivity('Updated', "Changed username from {$oldUsername} to {$this->username}", $this->user);
        }

        // Note: We DO NOT update email here, as requested it is read-only.

        // 3. Update Photo
        if ($this->new_photo) {
            $path = $this->new_photo->store('photos', 'public');
            $photoRecord = Photo::create(['photos' => $path]);

            $this->user->profile->update(['photo_id' => $photoRecord->id]);
            $this->logActivity('Updated', "Updated profile picture", $photoRecord);
        }

        // 4. Update Work Experiences
        foreach ($this->work_experiences as $work) {
            if (!empty($work['work_portfolio_id'])) {
                $portfolio = \App\Models\WorkPortfolio::find($work['work_portfolio_id']);
                $portfolio->update([
                    'designation' => $work['designation'],
                    'workplace' => $work['workplace'],
                    'duration' => $work['duration'],
                    'status' => $work['status'],
                    'description' => $work['description'],
                ]);
                
                if ($portfolio->wasChanged()) {
                    $this->logActivity('Updated', "Updated work experience details for {$work['designation']}", $portfolio);
                }
            } else {
                $newPortfolio = \App\Models\WorkPortfolio::create([
                    'designation' => $work['designation'],
                    'workplace' => $work['workplace'],
                    'duration' => $work['duration'],
                    'status' => $work['status'],
                    'description' => $work['description'],
                ]);

                \App\Models\PortfolioSet::create([
                    'profile_id' => $this->user->profile->id,
                    'work_portfolio_id' => $newPortfolio->id,
                ]);

                $this->logActivity('Created', "Added new work experience: {$work['designation']}", $newPortfolio);
            }
        }

        // 5. Update Engagements
        foreach ($this->engagements_list as $eng) {
            if(empty($eng['title'])) continue; 

            if (isset($eng['id']) && $eng['id']) {
                $engagement = \App\Models\Engagement::find($eng['id']);
                $engagement->update([
                    'title' => $eng['title'],
                    'description' => $eng['description'],
                ]);

                if ($engagement->wasChanged()) {
                    $this->logActivity('Updated', "Updated engagement details for {$eng['title']}", $engagement);
                }
            } else {
                $newEngagement = $this->user->engagements()->create([
                    'title' => $eng['title'],
                    'description' => $eng['description'],
                ]);
                $this->logActivity('Created', "Added new engagement: {$eng['title']}", $newEngagement);
            }
        }

        $this->user->refresh();
        $this->showEditModal = false;
        $this->new_photo = null; 
        session()->flash('message', 'Profile updated successfully!');
    }

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

    public function updatedSearch() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function render()
    {
        // Ensure relationships are loaded (I added 'role' here as you use it in the blade)
        $this->user->load(['profile', 'engagements', 'portfolioSet.workPortfolio', 'role']);
        
        $query = Course::query()
            ->where('implementer_id', $this->user->id)
            ->with(['coverPhotos', 'category', 'organization', 'tags'])
            ->where('status', 'Active');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('course_title', 'like', '%'.$this->search.'%')
                  ->orWhere('background', 'like', '%'.$this->search.'%')
                  ->orWhereHas('category', fn($sq) => $sq->where('category_name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('organization', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('tags', fn($sq) => $sq->where('tag', 'like', '%'.$this->search.'%'));
            });
        }

        switch ($this->sort) {
            case 'oldest': $query->oldest('start_date'); break;
            case 'a-z':    $query->orderBy('course_title', 'asc'); break;
            case 'z-a':    $query->orderBy('course_title', 'desc'); break;
            default:       $query->latest('start_date'); break;
        }

        return view('livewire.learner.profile', [
            'courses' => $query->paginate(5),
            'user' => $this->user, // <--- ADD THIS LINE to fix "Undefined variable"
        ])->layout('layouts.layout');
    }
}