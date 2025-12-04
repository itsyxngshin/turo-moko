<?php

use Illuminate\Support\Facades\Route;
// Ensure the Verify class is imported or replace it with the correct class
use App\Livewire\Auth\VerifyEmail; // Add this import at the top if Verify exists in this namespace

use Illuminate\Http\Request;
use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;


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
use App\Livewire\Learner\ArchivedCourses;
use App\Livewire\Learner\Notifications;

Route::get('/learner/activity/{id}', function($id) {
    return app(Activity::class)->mount($id)->html();
})->name('learner.activity.show');


Route::get('/learner/notifications', function () {
    return view('learner.notifications-page'); // Blade wrapper
})->name('learner.notifications');


use App\Http\Controllers\Admin\ViewCourseController;

use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\Implementors\ImplementorAddAnnouncementController;
use App\Http\Controllers\Implementors\ImplementorAddAssignmentController;
use App\Http\Controllers\Admin\CourseModerationController;
use App\Livewire\Implementors\CourseParticipants;


use App\Http\Controllers\AssessmentBuilderController;
use App\Http\Controllers\AssessmentResultsController;


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

    Route::get('/archived-courses', ArchivedCourses::class)->name('archived-courses');

    Route::get('/profile/edit', \App\Livewire\Learner\EditProfile::class)->name('profile.edit');

    Route::get('/assignment/{id}', function ($id) {
    return view('learner.assignment', compact('id'));
})->name('learner.assignment');
 

    
});




// -----------------------------
// Admin Pages
// -----------------------------

Route::prefix('implementor')->name('implementor.')->group(function () {
    Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/course-information/{course:course_code}', [ImplementorCourseInformationController::class, 'show'])
         ->name('course-information');

    Route::delete('/announcement/{course:course_code}', [ImplementorCourseInformationController::class, 'deleteAnnouncement'])
        ->name('implementor.announcement.delete');

    
    Route::get('/implementor/course/{courseId}/assignment/create', 
        [ImplementorAddAssignmentController::class, 'create']
    )->name('implementors.add-assignment');

    Route::delete('/modules/{module}', 
    [ImplementorCourseInformationController::class, 'destroy'])
    ->name('modules.destroy');

    
    Route::get('/myprofile', function () {
        return view('livewire.implementors.teacher-profile');
    })->name('myprofile');
    
    Route::get('/allcourses', function () {
        return view('livewire.implementors.all-courses');
    })->name('allcourses)');
    
    Route::get('/create-announcement', [ImplementorAddAnnouncementController::class, 'show'])
         ->name('add-announcement');

    
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/course/{course:course_code}/participants', CourseParticipants::class)
    ->name('course-participants');
    
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
    
    Route::get('/course-moderation', function () {
        return view('livewire.admin.course-moderation');
        })->name('admin.course-moderation');  
    
    Route::get('/moderation/course/{course}', [CourseModerationController::class, 'index'])
    ->name('moderation.course');


    Route::get('/course/{courseCode}', [ViewCourseController::class, 'show'])
    ->name('course.view');
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
        }); 
        */


// IMPLEMENTOR

Route::prefix('implementor')->group(function () {
    Route::get('/assessment-builder', [AssessmentBuilderController::class, 'create'])
        ->name('implementor.assessment-builder');
    
    Route::post('/assessment-builder', [AssessmentBuilderController::class, 'store'])
        ->name('implementor.assessment-builder.store');
    
    Route::put('/assessment-builder/{id}', [AssessmentBuilderController::class, 'update'])
        ->name('implementor.assessment-builder.update');

    // Assessment Results routes
    Route::get('/assessment-results', [AssessmentResultsController::class, 'index'])
        ->name('implementor.assessment-results');
    
    Route::get('/assessment-results/{quiz}', [AssessmentResultsController::class, 'show'])
        ->name('implementor.assessment-results.show');
    
    Route::post('/assessment-results/grade', [AssessmentResultsController::class, 'grade'])
        ->name('implementor.assessment-results.grade');
    
    Route::get('/assessment-results/{quiz}/export', [AssessmentResultsController::class, 'export'])
        ->name('implementor.assessment-results.export');
    
    // Test route to verify form submission
    Route::post('/test-form', function(Request $request) {
        return response()->json([
            'message' => 'Form received successfully!',
            'data' => $request->all()
        ]);
    })->name('implementor.test-form');
});