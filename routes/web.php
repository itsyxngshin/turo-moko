<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Course;

// Auth & Livewire
use App\Livewire\Auth\VerifyEmail;
use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser;
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Livewire\Implementors\CourseParticipants;
use App\Livewire\Implementors\AddAssignment;
use App\Livewire\Implementors\CourseGrades as ImplementorCourseGrades;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
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

// --- Admin ---
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\ViewCourse;
use App\Http\Controllers\Admin\ViewCourseController;
use App\Http\Controllers\Admin\CourseModerationController;

// --- Learner ---
use App\Http\Controllers\Learner\ClassesController;
use App\Http\Controllers\Learner\DashboardController;
use App\Http\Controllers\Learner\CoursesController;
use App\Http\Controllers\Learner\ActivitiesController;
use App\Livewire\Learner\ArchivedCourses;
use App\Livewire\Learner\EditProfile;
use App\Livewire\Learner\ShowAllCourses;
use App\Models\Activity;

// --- Implementor ---
use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\Implementors\ImplementorAddAnnouncementController;
use App\Http\Controllers\Implementors\ImplementorAddAssignmentController;
use App\Http\Controllers\Implementors\ImplementorEvaluationStatsController;
use App\Http\Controllers\Learner\CourseController as LearnerCourseController;

// Assessment Controllers (CRITICAL - DO NOT REMOVE)
use App\Http\Controllers\AssessmentBuilderController;
use App\Http\Controllers\AssessmentResultsController;
use App\Http\Controllers\Learner\LearnerAssessmentController;
use App\Livewire\Admin\CourseModeration;
use App\Livewire\Implementors\Profile as ImplementorProfile;

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

// TEMPORARY: Fake login route for testing
Route::get('/fake-login', function() {
    $user = \App\Models\User::where('role_id', 2)->first();
    if (!$user) {
        return 'No implementor user found. Run: php artisan db:seed --class=UsersTableSeeder';
    }
    \Auth::login($user);
    return redirect()->route('implementor.dashboard')->with('success', 'Logged in as ' . $user->email);
});

// TEMPORARY: Fake login as learner for testing
Route::get('/fake-login-learner', function() {
    $user = \App\Models\User::where('role_id', 1)->first();
    if (!$user) {
        return 'No learner user found. Run: php artisan db:seed --class=UsersTableSeeder';
    }
    \Auth::login($user);
    return redirect()->route('learner.hub')->with('success', 'Logged in as learner: ' . $user->email);
});

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


Route::middleware(['auth', 'role:learner', 'verified'])
    ->prefix('learner')
    ->name('learner.')
    ->group(function () {

    Route::get('/hub', [DashboardController::class, 'index'])->name('hub');
   // web.php
// web.php
Route::post('/course/{course}/enroll', [DashboardController::class, 'enroll'])
    ->name('course.enroll');





    Route::get('/classes', [ClassesController::class, 'index'])->name('classes');
    Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CoursesController::class, 'show'])->name('courses.show');
    Route::get('/completed-courses', [CoursesController::class, 'completed'])->name('courses.completed');
    Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities');
    Route::get('/archived-courses', ArchivedCourses::class)->name('archived-courses');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
 Route::get('/notifications', fn() => view('learner.notifications-page'))->name('notifications');
    // Livewire Views
    Route::get('/profile', fn() => view('livewire.learner.profile'))->name('profile');
    Route::get('/enrolled', fn() => view('livewire.learner.enrolled'))->name('enrolled');
    Route::get('/activity', fn() => view('livewire.learner.activities'))->name('activity');
    Route::get('/course', fn() => view('livewire.learner.course'))->name('course');
    Route::get('/activitytest', fn() => view('livewire.learner.activitytest'))->name('activitytest');
    Route::get('/submission', fn() => view('livewire.learner.submission'))->name('submission');
    Route::get('/assessment', fn() => view('livewire.learner.assessment'))->name('assessment');
    Route::get('/evaluation', fn() => view('livewire.learner.evaluation'))->name('evaluation');
    Route::get('/settings', fn() => view('livewire.learner.settings'))->name('settings');
    Route::get('/evaluation-status', fn() => view('livewire.learner.evaluation-status'))->name('evaluation-status');

    // Dynamic Pages
    Route::get('/activity/{id}', function($id) {
        return app(Activity::class)->mount($id)->html();
    })->name('activity.show');

    Route::get('/verify-email', VerifyEmail::class)->name('auth.verify'); 

    Route::get('/course/{course:course_code}', [\App\Http\Controllers\Learner\CourseController::class, 'show'])
        ->name('course.show');
    Route::get('/course/{course:course_code}/activity/{assignment}', \App\Livewire\Learner\ActivityDetail::class)
        ->name('activity.show');
    Route::get('/assessment/{quiz}', [LearnerAssessmentController::class, 'show'])
        ->name('assessment.show');
    Route::post('/assessment/{quiz}/submit', [LearnerAssessmentController::class, 'submit'])
        ->name('assessment.submit');
    Route::get('/assessment/{quiz}/result', [LearnerAssessmentController::class, 'result'])
        ->name('assessment.result');

Route::get('/suggested-courses', function() {
    $suggestedCourses = Course::latest()->get(); // Or add your filtering logic
    return view('livewire.learner.show-all-courses', compact('suggestedCourses'));
})->name('show-all-courses');



});



Route::prefix('implementor')->name('implementor.')->group(function () {
});


/*
|--------------------------------------------------------------------------
| IMPLEMENTOR ROUTES (TEACHERS)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| IMPLEMENTOR ROUTES (TEACHERS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:implementor', 'verified'])
    ->prefix('implementor')
    ->name('implementor.')
    ->group(function () {

    // --------------------------
    // Dashboard & Profile
    // --------------------------
    Route::get('/dashboard', [ImplementorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', ImplementorProfile::class)->name('profile');
    Route::get('/myprofile', fn() => view('livewire.implementors.teacher-profile'))->name('myprofile');
   
    Route::get('/assignment/{id}', fn($id) => view('learner.assignment', compact('id')))->name('assignment');

    // --------------------------
    // Course Management
    // --------------------------
    Route::get('/all-courses', fn() => view('livewire.implementors.all-courses'))->name('all-courses');
    Route::get('/course-information/{course:course_code}', [ImplementorCourseInformationController::class, 'show'])->name('course-information');
    Route::get('/course/{course:course_code}/grades', ImplementorCourseGrades::class)->name('course-grades');
    Route::get('/course/{course:course_code}/participants', CourseParticipants::class)->name('course-participants');

    // --------------------------
    // Create / Store Courses
    // --------------------------
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    // --------------------------
    // Assignments
    // --------------------------
    Route::get('/course/{course:course_code}/assignment/create', AddAssignment::class)->name('course.assignment.create');
    Route::get('/course/{course:course_code}/assignment/{assignment}/edit', \App\Livewire\Implementors\EditAssignment::class)->name('course.assignment.edit');
    Route::delete('/course/{course:course_code}/assignment/{assignment}', [ImplementorCourseInformationController::class, 'deleteAssignment'])->name('course.assignment.delete');

    // --------------------------
    // Modules
    // --------------------------
    Route::delete('/modules/{module}', [ImplementorCourseInformationController::class, 'destroy'])->name('modules.destroy');

    // --------------------------
    // Announcements
    // --------------------------
    Route::get('/create-announcement', [ImplementorAddAnnouncementController::class, 'show'])->name('add-announcement');
    Route::delete('/announcement/{course:course_code}', [ImplementorCourseInformationController::class, 'deleteAnnouncement'])->name('announcement.delete');

    // --------------------------
    // Assessment Builder
    // --------------------------
    Route::get('/assessment-builder', [AssessmentBuilderController::class, 'create'])->name('assessment-builder');
    Route::post('/assessment-builder', [AssessmentBuilderController::class, 'store'])->name('assessment-builder.store');
    Route::put('/assessment-builder/{id}', [AssessmentBuilderController::class, 'update'])->name('assessment-builder.update');
    Route::delete('/assessment-builder/{id}', [AssessmentBuilderController::class, 'destroy'])->name('assessment-builder.destroy');

    // --------------------------
    // Assessment Results
    // --------------------------
    Route::get('/assessment-results', [AssessmentResultsController::class, 'index'])->name('assessment-results');
    Route::get('/assessment-results/{quiz}', [AssessmentResultsController::class, 'show'])->name('assessment-results.show');
    Route::post('/assessment-results/grade', [AssessmentResultsController::class, 'grade'])->name('assessment-results.grade');
    Route::get('/assessment-results/{quiz}/export', [AssessmentResultsController::class, 'export'])->name('assessment-results.export');

    // --------------------------
    // Assignment Submissions
    // --------------------------
    Route::get('/assignment-submissions', [AssessmentResultsController::class, 'assignmentsIndex'])->name('assignment-submissions');
    Route::post('/assignment-results/grade', [AssessmentResultsController::class, 'gradeAssignment'])->name('assignment-results.grade');

    // --------------------------
    // Evaluation Statistics
    // --------------------------
    Route::get('/course/{course:course_code}/evaluation-stats', [ImplementorEvaluationStatsController::class, 'show'])->name('course.evaluation-stats');

    // --------------------------
    // Test route
    // --------------------------
    Route::post('/test-form', function(Request $request) {
        return response()->json([
            'message' => 'Form received successfully!',
            'data' => $request->all()
        ]);
    })->name('test-form');
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
 Route::get('/course-moderation/{id}', CourseModeration::class)
            ->name('course-moderation');
Route::get('/admin/course/{courseCode}', ViewCourse::class)
     ->name('view-course');
});