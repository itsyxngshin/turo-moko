<?php

use Illuminate\Support\Facades\Route;
// Ensure the Verify class is imported or replace it with the correct class
use App\Livewire\Auth\VerifyEmail; // Add this import at the top if Verify exists in this namespace

use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;

<<<<<<< Updated upstream
=======


// ✅ Import learner Livewire components
use App\Http\Livewire\Learner\Dashboard;
use App\Http\Livewire\Learner\Classes;
use App\Http\Livewire\Learner\Profile;
use App\Http\Livewire\Learner\Enrolled;
use App\Http\Livewire\Learner\Activity;
use App\Http\Livewire\Learner\Course;
use App\Http\Livewire\Learner\ActivityTest;
use App\Http\Livewire\Learner\Submission;
use App\Http\Livewire\Learner\Assessment;
use App\Http\Livewire\Learner\Evaluation;
use App\Http\Livewire\Learner\Settings;
use App\Http\Livewire\Learner\Activities;
use App\Http\Controllers\Learner\ClassesController; 
use App\Http\Controllers\Learner\ActivitiesController;
use App\Http\Controllers\Learner\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Learner\CoursesController;
use App\Http\Livewire\Learner\EvaluationStatus;



>>>>>>> Stashed changes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

// -----------------------------
// Public Pages
// -----------------------------
Route::get('/register', function () {
    return view('livewire.auth.register'); 
        })->name('auth.register');
        
Route::get('/login', function () {
        return view('livewire.auth.login');
        })->name('auth.login');

Route::get('/check', function () {
        return view('livewire.auth.verify');
        })->name('auth.verify');

Route::get('/verify-email', VerifyEmail::class)->name('auth.verify'); 

// -----------------------------
// Learner Pages
// -----------------------------
// Learner Routes using Controller (better than pointing to Livewire view)
Route::prefix('learner')->name('learner.')->group(function () {
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn() => view('learner.profile'))->name('profile');
    Route::get('/enrolled', fn() => view('learner.enrolled'))->name('enrolled');
    Route::get('/activity', fn() => view('learner.activity'))->name('activity');
    Route::get('/course', fn() => view('learner.course'))->name('course');
    Route::get('/activitytest', fn() => view('learner.activitytest'))->name('activitytest');
    Route::get('/submission', fn() => view('learner.submission'))->name('submission');
    Route::get('/assessment', fn() => view('learner.assessment'))->name('assessment');
    Route::get('/evaluation', fn() => view('learner.evaluation'))->name('evaluation');
    Route::get('/settings', fn() => view('learner.settings'))->name('settings');

    Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CoursesController::class, 'show'])->name('courses.show');
    Route::get('/completed-courses', [CoursesController::class, 'completed'])->name('courses.completed');

    // ✅ Correct route
    Route::get('/evaluation-status', function () {
        return view('learner.evaluation-status');
    })->name('evaluation-status');

    Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities');
});


<<<<<<< Updated upstream
// -----------------------------
// Admin Pages
// -----------------------------
=======

Route::prefix('implementor')->name('implementor.')->group(function () {
    Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/course-information/{course}', [ImplementorCourseInformationController::class, 'show'])
         ->name('course-information');

        
    Route::get('/myprofile', function () {
        return view('livewire.implementors.teacher-profile');
    })->name('myprofile');
    Route::get('/allcourses', function () {
        return view('livewire.implementors.all-courses');
    })->name('allcourses)');
    
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    
});

>>>>>>> Stashed changes
Route::prefix('admin')->group(function () {
    Route::get('/hub', function () {
        return view('livewire.admin.dashboard');
        })->name('admin.hub');

    Route::get('/implementors', function () {
        return view('livewire.admin.implementors');
        })->name('admin.implementors');
    
    Route::get('/enrollees', function () {
        return view('livewire.admin.enrollees'); 
        })->name('admin.enrollees');

    Route::get('/courses', function () {
        return view('livewire.admin.courses');
        })->name('admin.courses');
    
    Route::get('/reports', function () {
        return view('livewire.admin.reports');
        })->name('admin.reports');    

        /*
    //Route::get('/add-course', [AddCourse::class, 'create'])->name('addcourse');
    //Route::post('/add-course', [AddCourse::class, 'store'])->name('course.store'); 
    Route::get('/update-course', [ModifyCourse::class, 'edit'])->name('updatecourse');
    Route::put('/update-course', [ModifyCourse::class, 'update'])->name('course.update');
    Route::get('/update-user', [ModifyUser::class, 'edit'])->name('updateuser');
    Route::put('/update-user', [ModifyUser::class, 'update'])->name('user.update');
    Route::get('/view-user', [ViewUser::class, 'render'])->name('review');

    */
});

    Route::middleware(['auth', 'role:learner'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE LEARNER SIDE
        }); 
    
    Route::middleware(['auth', 'role:admin'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE ADMIN SIDE
        }); 
    
    Route::middleware(['auth', 'role:implementer'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE TEACHER/IMPLEMENTER SIDE
        }); 

    /*Route::middleware(['guest'])->group(function () {
        //OPEN FOR ALL / WEBSITE & LOGIN FACE
        Route::get('/register', [AuthController::class, 'registerView'])->name('register');
        Route::post('/passRegister', [AuthController::class, 'register'])->name('passRegister');
        Route::post('/shopRegister', [AuthController::class, 'shopRegister'])->name('shopRegister');
        Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/passLogin', [AuthController::class, 'login'])->name('passLogin');
        }); 

        */
