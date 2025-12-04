<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Livewire Auth Components
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Logout;
use App\Livewire\Learner\CourseMenu;
use App\Livewire\Auth\VerifyEmail; 
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\ResetPassword;

// Livewire Dashboards
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Learner\Dashboard as LearnerDashboard;
 
// Controllers
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\ViewCourseController;
use App\Http\Controllers\Admin\CourseModerationController;
use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\Implementors\ImplementorAddAnnouncementController;
use App\Http\Controllers\Learner\CourseController;
use App\Http\Controllers\Implementors\ImplementorAddAssignmentController;

use App\Livewire\Admin\Settings as AdminSettings;

//use App\Http\Controllers\Learner\CourseController;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Http\Controllers\AssessmentBuilderController;
use App\Http\Controllers\AssessmentResultsController;
//use App\Http\Controllers\CourseController;

// Chat
use App\Livewire\ChatFeature;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
use App\Livewire\Implementor\Dashboard as ImplementorDashboard;
use App\Livewire\Implementors\Profile as ImplementorProfile;

// Ensure the ForgotPassword class exists in the specified namespace or replace it with the correct class
// -----------------------------
// Public Pages
// -----------------------------

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('auth.login');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/forgot-password', ForgetPassword::class)->name('auth.forget-password');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Email Verification Page
Route::get('/verify-email', VerifyEmail::class)->name('auth.verify');


// -----------------------------
// Admin Pages
// -----------------------------

/*
    //Route::get('/add-course', [AddCourse::class, 'create'])->name('addcourse');
    //Route::post('/add-course', [AddCourse::class, 'store'])->name('course.store'); 
    Route::get('/update-course', [ModifyCourse::class, 'edit'])->name('updatecourse');
    Route::put('/update-course', [ModifyCourse::class, 'update'])->name('course.update');
    Route::get('/update-user', [ModifyUser::class, 'edit'])->name('updateuser');
    Route::put('/update-user', [ModifyUser::class, 'update'])->name('user.update');
    Route::get('/view-user', [ViewUser::class, 'render'])->name('review');

*/

Route::middleware(['auth', 'role:learner', 'verified'])->group(function () {
    Route::prefix('learner')->group(function () {
        Route::get('/hub', function () {
            return view('livewire.learner.dashboard');
            })->name('learner.hub');
        Route::get('/courses', CourseMenu::class)->name('learner.courses');

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
    }); 
    
Route::middleware(['auth', 'role:implementor', 'verified'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE TEACHER/IMPLEMENTER SIDE
    Route::prefix('implementer')->group(function () {
        Route::get('/hub', function () {
            return view(view: 'livewire.implementer.hub');
            })->name('implementer.hub');   
    });
        
}); 
Route::middleware(['auth'])->group(function () {

    // Chat System
    Route::get('/chat/{conversation?}', ChatFeature::class)->name('auth.chat');

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('auth.logout');

    /*
    |--------------------------------------------------------------------------
    | EMAIL VERIFICATION ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('email')->group(function () {

        // Notice page
        Route::get('/verify', VerifyEmail::class)->name('verification.notice');

        // Verification callback
        Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect()->route('homepage');
        })->middleware(['signed'])->name('verification.verify');

        // Resend verification link
        Route::post('/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        })->middleware(['throttle:6,1'])->name('verification.send');
    });
});


/*
|--------------------------------------------------------------------------
| LEARNER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:learner', 'verified'])->name('learner.')->group(function () {
    Route::prefix('learner')->group(function () {
        Route::get('/hub', LearnerDashboard::class)->name('hub');
        Route::get('/profile', fn() => view('livewire.learner.profile'))->name('profile');
        Route::get('/classes', fn() => view('livewire.learner.classes'))->name('classes');

        Route::get('/enrolled', fn() => view('livewire.learner.enrolled'))->name('enrolled');
        Route::get('/activity', fn() => view('livewire.learner.activities'))->name('activity');
        Route::get('/course', fn() => view('livewire.learner.course'))->name('course');
        Route::get('/activitytest', fn() => view('livewire.learner.activitytest'))->name('activitytest');
        Route::get('/submission', fn() => view('livewire.learner.submission'))->name('submission');
        Route::get('/assessment', fn() => view('livewire.learner.assessment'))->name('assessment');
        Route::get('/evaluation', fn() => view('livewire.learner.evaluation'))->name('evaluation');
        Route::get('/settings', fn() => view('livewire.learner.settings'))->name('settings');
    });
    
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin', 'verified'])->name('admin.')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/hub', AdminDashboard::class)->name('hub');

        Route::get('/implementors', fn() => view('livewire.admin.implementors'))->name('implementors');
        Route::get('/enrollees', fn() => view('livewire.admin.enrollees'))->name('enrollees');
        Route::get('/courses', fn() => view('livewire.admin.courses'))->name('courses');

        Route::get('/course-moderation', fn() => view('livewire.admin.course-moderation'))->name('course-moderation'); 

        Route::get('/moderation/course/{course}', [CourseModerationController::class, 'index'])
            ->name('moderation.course');

        Route::get('/course/{courseCode}', [ViewCourseController::class, 'show'])
            ->name('course.view');
        Route::get('/settings', AdminSettings::class)->name('settings');
    });
    
});


/*
|--------------------------------------------------------------------------
| IMPLEMENTOR ROUTES (FIXED & ORGANIZED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:implementor', 'verified'])
    ->prefix('implementor')
    ->name('implementor.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])
            ->name('hub');

        // Course information
        Route::get('/course-information/{course:course_code}', 
            [ImplementorCourseInformationController::class, 'show'])
            ->name('course-information');

        // Delete Announcement
        Route::delete('/announcement/{course:course_code}', 
            [ImplementorCourseInformationController::class, 'deleteAnnouncement'])
            ->name('announcement.delete');

        // Create Assignment
        Route::get('/course/{courseId}/assignment/create',
            [ImplementorAddAssignmentController::class, 'create'])
            ->name('add-assignment');

        // Delete Module
        Route::delete('/modules/{module}',
            [ImplementorCourseInformationController::class, 'destroy'])
            ->name('modules.destroy');

         Route::get('/profile', ImplementorProfile::class) 
            ->name('implementor.profile');   

        // Implementor profile/pages
        Route::get('/myprofile', fn() => view('livewire.implementors.teacher-profile'))
            ->name('myprofile');

        Route::get('/allcourses', fn() => view('livewire.implementors.all-courses'))
            ->name('allcourses');

        // New announcement page
        Route::get('/create-announcement', 
            [ImplementorAddAnnouncementController::class, 'show'])
            ->name('add-announcement');

        // Course creation
        Route::get('/courses/create', [CourseController::class, 'create'])
            ->name('courses.create');

        Route::post('/courses', [CourseController::class, 'store'])
            ->name('courses.store');
});

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