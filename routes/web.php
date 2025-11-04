<?php 
 
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Ensure the Verify class is imported or replace it with the correct class
use App\Livewire\Auth\VerifyEmail; // Add this import at the top if Verify exists in this namespace

use App\Http\Livewire\Admin\Modal\ModifyCourse;
use App\Http\Livewire\Admin\Modal\ModifyUser; // Ensure this class exists in the specified namespace
use App\Http\Livewire\Admin\Modal\ViewUser;
use App\Http\Controllers\AuthController; // Ensure this class exists in the specified namespace
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Verify; // Ensure this class exists in the specified namespace
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Learner\Dashboard as LearnerDashboard;
use App\Livewire\Implementer\Dashboard as ImplementerDashboard;
// Ensure the ForgotPassword class exists in the specified namespace or replace it with the correct class
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\ResetPassword;
// -----------------------------
// Public Pages
// -----------------------------

Route::get('/', function () {
    return view('welcome'); 
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('auth.login');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/forgot-password', ForgetPassword::class)->name('auth.forget-password'); 
    Route::get('/reset-password/{token}', ResetPassword::class)->name('auth.reset-password'); 
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

Route::middleware(['auth', 'role:learner'])->group(function () {
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
    
Route::middleware(['auth', 'role:admin'])->group(function () {
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
        
        Route::get('/reports', function () {
            return view('livewire.admin.reports');
            })->name('admin.reports');    
    });
}); 
    
Route::middleware(['auth', 'role:implementer'])->group(function () {
        //LINK THE BLADES EXCLUSIVE FOR THE TEACHER/IMPLEMENTER SIDE
    Route::prefix('admin')->group(function () {
        Route::get('/hub', function () {
            return view(view: 'livewire.implementer.profile');
            })->name('implementer.hub');   
        });
}); 
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function (Request $request) {
    Auth::logout();  
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/login');
        })->name('auth.logout');
}); 

