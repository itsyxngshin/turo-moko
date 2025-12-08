<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| IMPORTS
|--------------------------------------------------------------------------
*/

// --- Auth Livewire ---
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;

// --- Admin ---
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Http\Controllers\Admin\ViewCourseController;
use App\Http\Controllers\Admin\CourseModerationController;

// --- Learner ---
use App\Http\Controllers\Learner\ClassesController;
use App\Http\Controllers\Learner\DashboardController;
use App\Http\Controllers\Learner\CoursesController;
use App\Http\Controllers\Learner\ActivitiesController;
use App\Livewire\Learner\ArchivedCourses;
use App\Livewire\Learner\EditProfile;
use App\Models\Activity;

// --- Implementor ---
use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\Implementors\ImplementorAddAnnouncementController;
use App\Http\Controllers\Implementors\ImplementorAddAssignmentController;
use App\Livewire\Admin\CourseModeration;
use App\Livewire\Implementors\CourseParticipants;
use App\Livewire\Implementors\Profile as ImplementorProfile;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentBuilderController;
use App\Http\Controllers\AssessmentResultsController;

// --- Shared/Chat ---
use App\Livewire\ChatFeature;
use App\Http\Controllers\Auth\LogoutController;


/*
|--------------------------------------------------------------------------
| PUBLIC & GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('auth.login');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/forgot-password', ForgetPassword::class)->name('auth.forget-password');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED GLOBAL ROUTES (Any Logged in User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('auth.logout');
    
    // Chat System
    Route::get('/chat/{conversation?}', ChatFeature::class)->name('auth.chat');

    // Email Verification Routes
    Route::prefix('email')->group(function () {
        Route::get('/verify', VerifyEmail::class)->name('verification.notice');

        Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect()->route('homepage');
        })->middleware(['signed'])->name('verification.verify');

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
Route::middleware(['auth', 'role:learner', 'verified'])
    ->prefix('learner')
    ->name('learner.')
    ->group(function () {

    Route::get('/hub', [DashboardController::class, 'index'])->name('hub');
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes');
    Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CoursesController::class, 'show'])->name('course.show');
    Route::get('/completed-courses', [CoursesController::class, 'completed'])->name('courses.completed');
    Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities');
    Route::get('/archived-courses', ArchivedCourses::class)->name('archived-courses');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
Route::post('/course/{course}/enroll', [DashboardController::class, 'enroll'])
    ->name('course.enroll');


    // Simple Views
    Route::get('/profile', fn() => view('learner.profile'))->name('profile');
    Route::get('/enrolled', fn() => view('learner.enrolled'))->name('enrolled');
    Route::get('/activity', fn() => view('learner.activity'))->name('activity');
    Route::get('/course', fn() => view('learner.course'))->name('course');
    Route::get('/activitytest', fn() => view('learner.activitytest'))->name('activitytest');
    Route::get('/submission', fn() => view('learner.submission'))->name('submission');
    Route::get('/assessment', fn() => view('learner.assessment'))->name('assessment');
    Route::get('/evaluation', fn() => view('learner.evaluation'))->name('evaluation');
    Route::get('/settings', fn() => view('learner.settings'))->name('settings');
    Route::get('/evaluation-status', fn() => view('learner.evaluation-status'))->name('evaluation-status');

    // Dynamic Pages
    Route::get('/activity/{id}', function($id) {
        return app(Activity::class)->mount($id)->html();
    })->name('activity.show');
Route::get('/suggested-courses', function() {
    $suggestedCourses = Course::latest()->get(); // Or add your filtering logic
    return view('livewire.learner.show-all-courses', compact('suggestedCourses'));
})->name('show-all-courses');
    Route::get('/notifications', fn() => view('learner.notifications-page'))->name('notifications');
    Route::get('/assignment/{id}', fn($id) => view('learner.assignment', compact('id')))->name('assignment'); 
});


/*
|--------------------------------------------------------------------------
| IMPLEMENTOR ROUTES (TEACHERS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:implementor', 'verified'])
    ->prefix('implementor')
    ->name('implementor.')
    ->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])->name('dashboard'); // NOTE: Changed from 'hub' to 'dashboard' to match controller, or alias it.
    Route::get('/profile', ImplementorProfile::class)->name('profile'); 
    Route::get('/myprofile', fn() => view('livewire.implementors.teacher-profile'))->name('myprofile');

    // Course Management
    Route::get('/all-courses', fn() => view('livewire.implementors.all-courses'))->name('all-courses');
    
    // Create/Store Courses
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    // Specific Course Actions
    Route::get('/course-information/{course:course_code}', [ImplementorCourseInformationController::class, 'show'])->name('course-information');
    Route::get('/course/{course:course_code}/participants', CourseParticipants::class)->name('course-participants');
    
    // Modules & Assignments
    Route::delete('/modules/{module}', [ImplementorCourseInformationController::class, 'destroy'])->name('modules.destroy');
    Route::get('/course/{courseId}/assignment/create', [ImplementorAddAssignmentController::class, 'create'])->name('add-assignment');

    // Announcements
    Route::get('/create-announcement', [ImplementorAddAnnouncementController::class, 'show'])->name('add-announcement');
    Route::delete('/announcement/{course:course_code}', [ImplementorCourseInformationController::class, 'deleteAnnouncement'])->name('announcement.delete');

    // Assessment Builder
    Route::get('/assessment-builder', [AssessmentBuilderController::class, 'create'])->name('assessment-builder');
    Route::post('/assessment-builder', [AssessmentBuilderController::class, 'store'])->name('assessment-builder.store');
    Route::put('/assessment-builder/{id}', [AssessmentBuilderController::class, 'update'])->name('assessment-builder.update');

    // Assessment Results
    Route::get('/assessment-results', [AssessmentResultsController::class, 'index'])->name('assessment-results');
    Route::get('/assessment-results/{quiz}', [AssessmentResultsController::class, 'show'])->name('assessment-results.show');
    Route::post('/assessment-results/grade', [AssessmentResultsController::class, 'grade'])->name('assessment-results.grade');
    Route::get('/assessment-results/{quiz}/export', [AssessmentResultsController::class, 'export'])->name('assessment-results.export');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    Route::get('/hub', AdminDashboard::class)->name('hub');
    Route::get('/settings', AdminSettings::class)->name('settings');

    // Management Views
    Route::get('/implementors', fn() => view('livewire.admin.implementors'))->name('implementors');
    Route::get('/enrollees', fn() => view('livewire.admin.enrollees'))->name('enrollees');
    Route::get('/courses', fn() => view('livewire.admin.courses'))->name('courses');
    
    // Moderation
Route::get('/course-moderation/{id}', CourseModeration::class)->name('course-moderation');
    Route::get('/course/{courseCode}', [ViewCourseController::class, 'show'])->name('course.view');
});