<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes for testing front ends //
Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

// End //

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
    Route::get('/courses', function () {
        return view('livewire.implementors.courses');
    })->name('courses');
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
    });