<?php 
 
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest; // Add this import to fix the error
// Ensure the Verify class is imported or replace it with the correct class
use App\Livewire\Auth\VerifyEmail; // Add this import at the top if Verify exists in this namespace

use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Http\Controllers\AuthController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\Admin\ViewCourseController;

use App\Http\Controllers\Implementors\ImplementorDashboardController;
use App\Http\Controllers\Implementors\ImplementorCourseInformationController;
use App\Http\Controllers\Implementors\ImplementorAddAnnouncementController;
use App\Http\Controllers\Learner\CourseController;
use App\Http\Controllers\Implementors\ImplementorAddAssignmentController;
use App\Http\Controllers\Admin\CourseModerationController;


use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Logout;
use App\Http\Controllers\Auth\LogoutController; // Add this import to fix the error
use App\Livewire\Auth\Verify;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Learner\Dashboard as LearnerDashboard;
use App\Livewire\Implementer\Dashboard as ImplementerDashboard;
// Ensure the ForgotPassword class exists in the specified namespace or replace it with the correct class
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\ChatFeature; // Ensure this class exists in the specified namespace
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

Route::get('/password-reset', function () {
    return view('auth.password-request');  
})->name('auth.password-request');



Route::get('/check', function () {
        return view('livewire.auth.verify');
        })->name('auth.verify');

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
    
Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE ADMIN SIDE
    Route::prefix('admin')->group(function () {
        Route::get('/hub', AdminDashboard::class)->name('admin.hub');

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
    Route::prefix('implementer')->group(function () {
        Route::get('/hub', function () {
            return view(view: 'livewire.implementer.profile');
            })->name('implementer.hub');   
        });
}); 
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{conversation?}', ChatFeature::class)->name('auth.chat'); 
    //Route::get('/chat/{conversation}', Chat::class)->name('chat.view'); // Ensure the Chat class is correctly imported and exists
    Route::post('/logout', [LogoutController::class, 'logout'])->name('auth.logout');
    Route::prefix('email')->group(function () {
       // Email verification notice page
        Route::get('/verify', VerifyEmail::class)->name('verification.notice');
        
        // Email verification handler (link clicked) 
        Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill(); // Mark email as verified
            return redirect()->route('homepage'); // redirect anywhere you want
        })->middleware(['signed'])->name('verification.verify');

        // Resend verification email
        Route::post('/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        })->middleware(['throttle:6,1'])->name('verification.send');
    });
}); 

 





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
    
});
