<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Conversation;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'profile_id', 'role_id', 'username', 'email', 'password', 'phonenum', 'verification_code'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
  

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function enrollments()
{
    return $this->hasMany(CourseEnrollee::class, 'enrollee_id');
}

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function courses()
    {
        // 2nd argument: Foreign key on courses table
        // 3rd argument: Local key on users table
        return $this->hasMany(Course::class, 'implementer_id', 'id');
    }

    public function portfolioSet() {
        // Since PortfolioSet belongs to a Profile, and User belongs to a Profile,
        // we access it via the Profile relationship.
        return $this->hasOneThrough(
            PortfolioSet::class, 
            Profile::class, 
            'id', // Foreign key on profiles table (user.profile_id is actually on users table, so we might need a different approach if schema is strict)
            'profile_id', // Foreign key on portfolio_sets table
            'profile_id', // Local key on users table
            'id' // Local key on profiles table
        );
        
        // ALTERNATIVE (Simpler if you just chain in Blade):
        // You don't strictly need this method if you access it like $user->profile->portfolioSet
    }

    public function getProfileRouteAttribute()
    {
        return match($this->role_id) {
            3 => 'homepage',   // If Role 3 (Admin)
            2 => 'implementor.profile', // If Role 2 (Teacher/Implementer)
            1 => 'learner.profile', // If Role 1 (Student/Learner)
            default => 'homepage', // Fallback
        };
    }

    public function enrolledCourses(){
    // 1. Related Model: Course
    // 2. Pivot Table: course_enrollees
    // 3. Foreign Key on Pivot (User): enrollee_id
    // 4. Foreign Key on Pivot (Course): course_id
    return $this->belongsToMany(Course::class, 'course_enrollees', 'enrollee_id', 'course_id')
                ->withPivot('status', 'enrollment_date', 'completion_date')
                ->withTimestamps();
}
    public function recentCourses()
    {
    return $this->belongsToMany(Course::class, 'course_user')
                ->withPivot('last_accessed')
                ->orderByDesc('pivot_last_accessed');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
