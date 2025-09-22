<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Http\Controllers\AssessmentBuilderController;
use App\Http\Controllers\CourseController;

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
    Route::get('/dashboard', fn() => view('learner.dashboard'))->name('learner.dashboard');
    Route::get('/classes', [CourseController::class, 'index'])->name('learner.classes');
    Route::get('/profile', fn() => view('learner.profile'))->name('learner.profile');
    Route::get('/enrolled', fn() => view('learner.enrolled'))->name('learner.enrolled');
    Route::get('/activity', fn() => view('learner.activity'))->name('learner.activity');
    Route::get('/course', fn() => view('learner.course'))->name('learner.course');
    Route::get('/activitytest', fn() => view('learner.activitytest'))->name('learner.activitytest');
    Route::get('/submission', fn() => view('learner.submission'))->name('learner.submission');
    Route::get('/assessment', fn() => view('learner.assessment'))->name('learner.assessment');
    Route::get('/evaluation', fn() => view('learner.evaluation'))->name('learner.evaluation');
    Route::get('/settings', fn() => view('learner.settings'))->name('learner.settings');
});

// -----------------------------
// Admin Pages
// -----------------------------
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



// IMPLEMENTOR

Route::prefix('implementor')->group(function () {
    Route::get('/assessment-builder', [AssessmentBuilderController::class, 'create'])
        ->name('implementor.assessment-builder');
    
    Route::post('/assessment-builder', [AssessmentBuilderController::class, 'store'])
        ->name('implementor.assessment-builder.store');
    
    Route::put('/assessment-builder/{id}', [AssessmentBuilderController::class, 'update'])
        ->name('implementor.assessment-builder.update');
    
    // Test route to verify form submission
    Route::post('/test-form', function(Request $request) {
        return response()->json([
            'message' => 'Form received successfully!',
            'data' => $request->all()
        ]);
    })->name('implementor.test-form');
});