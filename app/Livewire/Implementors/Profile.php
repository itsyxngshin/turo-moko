<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\WorkPortfolio;
use App\Models\Engagement;


#[Layout('layouts.implementer')] 
class Profile extends Component
{
    use WithPagination; // 2. Use the trait

    public $user;
    public $active_engagement;
    
    // 3. Add Search & Sort properties
    public $search = '';
    public $sort = 'latest'; 
    public $showEditModal = false;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $portfolio_link;
    
    // DATA LISTS
    public $work_experiences = [];
    public $engagements_list = []; 

    public function mount()
    {
        if (!Auth::check()) return redirect()->route('login');

        // Eager load the chain: Profile -> PortfolioSets -> WorkPortfolio
        $this->user = User::with([
            'profile.portfolioSets.workPortfolio', 
            'engagements'
        ])->find(Auth::id());
        
        $this->active_engagement = $this->user->engagements->last();
    }

    public function openEditModal()
    {
        $this->first_name = $this->user->profile->first_name;
        $this->last_name = $this->user->profile->last_name;
        $this->middle_name = $this->user->profile->middle_name;
        
        // --- NEW LOGIC: Flatten the relationship for the Form ---
        $this->work_experiences = [];

        if ($this->user->profile->portfolioSets) {
            foreach ($this->user->profile->portfolioSets as $set) {
                // We grab the actual data from the related 'workPortfolio' model
                if ($set->workPortfolio) {
                    $this->work_experiences[] = [
                        'portfolio_set_id' => $set->id, // We need this to identify the link
                        'work_portfolio_id' => $set->work_portfolio_id, // We need this to update the data
                        'designation' => $set->workPortfolio->designation,
                        'workplace' => $set->workPortfolio->workplace,
                        'duration' => $set->workPortfolio->duration,
                        'status' => $set->workPortfolio->status,
                        'description' => $set->workPortfolio->description,
                    ];
                }
            }
        }

        // Load Engagements (Same as before)
        $this->engagements_list = $this->user->engagements->toArray();

        $this->showEditModal = true;
    }

    public function addWorkExperience()
    {
        $this->work_experiences[] = [
            'portfolio_set_id' => null, // null means it's new
            'work_portfolio_id' => null,
            'designation' => '',
            'duration' => '',
            'status' => 'Active',
            'description' => ''
        ];
    }

    public function removeWorkExperience($index)
    {
        $item = $this->work_experiences[$index];

        // If it exists in DB, delete the Connector AND the Data
        if (!empty($item['portfolio_set_id'])) {
            $set = \App\Models\PortfolioSet::find($item['portfolio_set_id']);
            if ($set) {
                // Delete the WorkPortfolio data record
                if ($set->work_portfolio_id) {
                    \App\Models\WorkPortfolio::find($set->work_portfolio_id)?->delete();
                }
                // Delete the connector
                $set->delete();
            }
        }
        
        unset($this->work_experiences[$index]);
        $this->work_experiences = array_values($this->work_experiences);
    }

    public function saveProfile()
    {
        // 1. Update Profile Name
        $this->user->profile->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->last_name,
            'last_name' => $this->last_name,
        ]);

        // 2. Update Work Experiences (The Complex Part)
        foreach ($this->work_experiences as $work) {
            
            // A. UPDATE EXISTING
            if (!empty($work['work_portfolio_id'])) {
                \App\Models\WorkPortfolio::find($work['work_portfolio_id'])->update([
                    'designation' => $work['designation'],
                    'workplace' => $work['workplace'],
                    'duration' => $work['duration'],
                    'status' => $work['status'],
                    'description' => $work['description'],
                ]);
            } 
            // B. CREATE NEW
            else {
                // 1. Create the Data Row
                $newPortfolio = \App\Models\WorkPortfolio::create([
                    'designation' => $work['designation'],
                    'workplace' => $work['workplace'],
                    'duration' => $work['duration'],
                    'status' => $work['status'],
                    'description' => $work['description'],
                ]);

                // 2. Create the Connector Row (PortfolioSet)
                \App\Models\PortfolioSet::create([
                    'profile_id' => $this->user->profile->id,
                    'work_portfolio_id' => $newPortfolio->id,
                ]);
            }
        }

        // 3. Update Engagements (Same as before)
        foreach ($this->engagements_list as $eng) {
            if (isset($eng['id']) && $eng['id']) {
                \App\Models\Engagement::find($eng['id'])->update([
                    'title' => $eng['title'],
                    'description' => $eng['description'],
                ]);
            } else {
                $this->user->engagements()->create([
                    'title' => $eng['title'],
                    'description' => $eng['description'],
                ]);
            }
        }

        $this->user->refresh();
        $this->showEditModal = false;
        session()->flash('message', 'Profile updated successfully!');
    }

    // Reset pagination when searching/sorting
    public function updatedSearch() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    #[Layout('layouts.implementer')] 
    public function render()
    {
        $this->user->load(['profile', 'engagements', 'portfolioSet.workPortfolio']);
        // 4. Build the query scoped to THIS USER (implementer_id)
        $query = Course::query()
            ->where('implementer_id', $this->user->id) // <--- CRITICAL: Only this teacher's courses
            ->with(['coverPhoto', 'category', 'organization', 'tags'])
            ->where('status', 'Active');

        // 5. Apply Search Logic (Same as your CourseMenu)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('course_title', 'like', '%'.$this->search.'%')
                  ->orWhere('background', 'like', '%'.$this->search.'%')
                  ->orWhereHas('category', fn($sq) => $sq->where('category_name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('organization', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('tags', fn($sq) => $sq->where('tag', 'like', '%'.$this->search.'%'));
            });
        }

        // 6. Apply Sort Logic
        switch ($this->sort) {
            case 'oldest': $query->oldest('start_date'); break;
            case 'a-z':    $query->orderBy('course_title', 'asc'); break;
            case 'z-a':    $query->orderBy('course_title', 'desc'); break;
            default:       $query->latest('start_date'); break;
        }

        return view('livewire.implementer.profile', [
            'courses' => $query->paginate(5) // Pagination!
        ]);
    }
}
