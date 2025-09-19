<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;

Route::get('/', function () {
    return view('welcome');
});

// -----------------------------
// Public Pages
// -----------------------------
Route::get('/signup', fn() => view('signup'))->name('signup');
Route::get('/login', fn() => view('login'))->name('login');


// -----------------------------
// Learner Pages
// -----------------------------
Route::prefix('learner')->group(function () {
        // ADMIN DASHBOARD
    Route::get('/classes', function () {
        return view('learner.classes');
    })->name('learner.classes');

    Route::get('/dashboard', function () {
        return view('learner.dashboard');
    })->name('learner.dashboard');

    Route::get('/profile', function () {
        return view('learner.profile');
    })->name('learner.profile');

    Route::get('/enrolled', function () {
        return view('learner.enrolled');
    })->name('learner.enrolled');

    Route::get('/activity', function () {
        return view('learner.activity');
    })->name('learner.activity');

    Route::get('/course', function () {
        return view('learner.course');
    })->name('learner.course');

    Route::get('/activitytest', function () {
        return view('learner.activitytest');
    })->name('learner.activitytest');

    Route::get('/submission', function () {
        return view('learner.submission');
    })->name('learner.submission');

    Route::get('/assessment', function () {
        return view('learner.assessment');
    })->name('learner.assessment');

    Route::get('/evaluation', function () {
        return view('learner.evaluation');
    })->name('learner.evaluation');
    });

Route::prefix('implementor')->name('implementor.')->group(function () {
    Route::get('/course-information', function () {
        return view('livewire.implementors.implementor-course-details');
    })->name('course-information');
    Route::get('/dashboard', function () {
        return view('livewire.implementors.implementor-dashboard');
    })->name('dashboard');
    Route::get('/myprofile', function () {
        return view('livewire.implementors.teacher-profile');
    })->name('myprofile');
    Route::get('/allcourses', function () {
        return view('livewire.implementors.all-courses');
    })->name('allcourses)');
    
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

    //Route::get('/add-course', [AddCourse::class, 'create'])->name('addcourse');
    //Route::post('/add-course', [AddCourse::class, 'store'])->name('course.store'); 
    Route::get('/update-course', [ModifyCourse::class, 'edit'])->name('updatecourse');
    Route::put('/update-course', [ModifyCourse::class, 'update'])->name('course.update');
    Route::get('/update-user', [ModifyUser::class, 'edit'])->name('updateuser');
    Route::put('/update-user', [ModifyUser::class, 'update'])->name('user.update');
    Route::get('/view-user', [ViewUser::class, 'render'])->name('review');
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

    Route::middleware(['guest'])->group(function () {
        //OPEN FOR ALL / WEBSITE & LOGIN FACE
        }); 

