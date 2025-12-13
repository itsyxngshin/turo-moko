<?php

namespace App\Livewire\Learner;

use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Photo; 
use App\Models\WorkPortfolio;
use App\Models\PortfolioSet; // Explicit import
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
    public $first_name, $last_name, $middle_name, $username, $email, $new_photo;
    public $work_experiences = [];
    public $engagements_list = []; 

    // Course Data
    public $activeCourses; 
    public $activeCoursesCount = 0;

    public function mount()
    {
        if (!Auth::check()) return redirect()->route('login');

        // 1. Load User & Profile Data
        $this->user = User::with([
            'profile.portfolioSets.workPortfolio', 
            'engagements'
        ])->find(Auth::id());
        
        $this->active_engagement = $this->user->engagements->last();

        // 2. Initialize Edit Form
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;

        // 3. Load Form Lists
        $this->loadWorkExperiences();
        $this->engagements_list = $this->user->engagements->toArray();

        // ------------------------------------------------------------------
        // [FETCH ACTIVE COURSES VIA PIVOT]
        // ------------------------------------------------------------------
        $this->activeCourses = $this->user->enrolledCourses()
            ->wherePivot('status', 'Active') 
            ->with([
                'activeCoverPhoto',       
                'category', 
                'implementer.profile'     
            ])
            ->orderByPivot('enrollment_date', 'desc') 
            ->get();

        $this->activeCoursesCount = $this->activeCourses->count();
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
        // 1. RELOAD USER FRESH (Critical Fix)
        // We must re-fetch the user and relationships to ensure they aren't lost during Livewire updates
        $this->user = User::with([
            'profile.portfolioSets.workPortfolio', 
            'engagements'
        ])->find(Auth::id());

        // 2. Safety Check
        if (!$this->user || !$this->user->profile) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Profile not found.'
            ]);
            return;
        }

        // 3. Assign Data
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;

        // 4. Load Lists
        $this->loadWorkExperiences();
        $this->engagements_list = $this->user->engagements->toArray();

        // 5. Open Modal
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

    // ==========================================================
    // FIX APPLIED HERE: Delete Child (Set) before Parent (Work)
    // ==========================================================
    public function removeWorkExperience($index)
    {
        $item = $this->work_experiences[$index];
        
        // Check if it's an existing database record
        if (!empty($item['portfolio_set_id'])) {
            
            $set = PortfolioSet::find($item['portfolio_set_id']);
            
            if ($set) {
                // 1. Capture the Work Portfolio ID first
                $workPortfolioId = $set->work_portfolio_id;
                
                // 2. DELETE THE LINKING RECORD FIRST (The Child)
                // This removes the foreign key constraint immediately.
                $set->delete();

                // 3. NOW DELETE THE PARENT RECORD (The Work Portfolio)
                if ($workPortfolioId) {
                    WorkPortfolio::find($workPortfolioId)?->delete();
                }
            }
        }
        
        // Remove from local array to update UI
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
            Engagement::find($item['id'])?->delete();
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
                WorkPortfolio::find($work['work_portfolio_id'])->update($work);
            } else {
                $new = WorkPortfolio::create($work);
                PortfolioSet::create([
                    'profile_id' => $this->user->profile->id,
                    'work_portfolio_id' => $new->id,
                ]);
            }
        }

        // 5. Update Engagements
        foreach ($this->engagements_list as $eng) {
            if(empty($eng['title'])) continue; 
            if (isset($eng['id']) && $eng['id']) {
                Engagement::find($eng['id'])->update(['title' => $eng['title'], 'description' => $eng['description']]);
            } else {
                $this->user->engagements()->create(['title' => $eng['title'], 'description' => $eng['description']]);
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

    public function render()
    {
        return view('livewire.learner.profile')->layout('layouts.layout');
    }
}