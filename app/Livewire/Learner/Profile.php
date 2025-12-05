<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $activeCourses = 0;
    public $archivedCourses = 0;
    public $courses = [];
    public $showProfileModal = false;
    public $newProfileImage;

    public $username;
    public $email;
    public $phone;
    public $bio;
    public $password;

    protected $rules = [
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string|max:500',
        'password' => 'nullable|string|min:6',
        'newProfileImage' => 'nullable|image|max:1024',
    ];

    // Listener for opening the modal from another component if needed
    protected $listeners = [
        'openProfileModal' => 'showModal',
    ];

    public function showModal()
    {
        $this->showProfileModal = true;
        $this->loadUserData();
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadUserData();

        // Example dummy courses
        $this->activeCourses = 3;
        $this->archivedCourses = 1;

        $this->courses = [
            [
                'name' => 'Introduction to Programming',
                'semester' => '1st Semester',
                'description' => 'Learn the basics of programming and algorithms.',
                'progress' => 80,
                'image' => asset('images/sample-course-1.jpg'),
            ],
            [
                'name' => 'Web Development Fundamentals',
                'semester' => '2nd Semester',
                'description' => 'Understand front-end and back-end web technologies.',
                'progress' => 45,
                'image' => asset('images/sample-course-2.jpg'),
            ],
        ];
    }

    public function loadUserData()
    {
        $this->user = Auth::user() ?? (object) [
            'username' => 'DemoUser',
            'email' => 'demo@example.com',
            'profile_image' => 'images/default-profile.png',
            'phone' => '09213456776',
            'bio' => 'No bio available.',
        ];

        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone ?? '';
        $this->bio = $this->user->bio ?? '';
    }

    public function save()
    {
        $this->validate();

        if (Auth::check()) {
            $user = Auth::user();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->bio = $this->bio;

            if ($this->password) {
                $user->password = bcrypt($this->password);
            }

            if ($this->newProfileImage) {
                $path = $this->newProfileImage->store('profile-images', 'public');
                $user->profile_image = $path;
            }

            $user->save();
        }

        $this->showProfileModal = false;
        $this->loadUserData();
        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.learner.profile')
            ->layout('layouts.layout2');
    }
}
