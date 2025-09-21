<?php

use Illuminate\Support\Facades\Route;
// Ensure the Verify class is imported or replace it with the correct class
use App\Livewire\Auth\VerifyEmail; // Add this import at the top if Verify exists in this namespace

use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Http\Controllers\AuthController; // Ensure this class exists in the specified namespace


use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\CourseController;


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

Route::prefix('learner')->group(function () {
    Route::get('/hub', function () {
        return view('livewire.learner.dashboard');
        })->name('learner.hub');

    Route::get('/profile', function () {
        return view('livewire.learner.profile');
        })->name('learner.profile');
    
    Route::get('/classes', function () {
        return view('livewire.learner.classes');
        })->name('learner.classes');
    
    Route::get('/enrolled', fn() => view('livewire.learner.enrolled'))->name('learner.enrolled');
    Route::get('/activity', fn() => view('livewire.learner.activities'))->name('learner.activity');
    Route::get('/course', fn() => view('livewire.learner.course'))->name('learner.course');
    Route::get('/activitytest', fn() => view('livewire.learner.activitytest'))->name('learner.activitytest');
    Route::get('/submission', fn() => view('livewire.learner.submission'))->name('learner.submission');
    Route::get('/assessment', fn() => view('livewire.learner.assessment'))->name('learner.assessment');
    Route::get('/evaluation', fn() => view('livewire.learner.evaluation'))->name('learner.evaluation');
    Route::get('/settings', fn() => view('livewire.learner.settings'))->name('learner.settings');
});


// -----------------------------
// Admin Pages
// -----------------------------

Route::prefix('implementor')->name('implementor.')->group(function () {
    Route::get('/course-information/{course}', [ImplementorCourseInformationController::class, 'show'])
         ->name('course-information');

    Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])
        ->name('dashboard');
        
    Route::get('/myprofile', function () {
        return view('livewire.implementors.teacher-profile');
    })->name('myprofile');
    Route::get('/allcourses', function () {
        return view('livewire.implementors.all-courses');
    })->name('allcourses)');
    
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    
});

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
