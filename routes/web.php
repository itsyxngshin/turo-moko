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

Route::get('/learner/classes', function () {
    return view('learner.classes');
})->name('learner.classes');

Route::get('/learner/dashboard', function () {
    return view('learner.dashboard');
})->name('learner.dashboard');

Route::get('/learner/profile', function () {
    return view('learner.profile');
})->name('learner.profile');

Route::get('/learner/enrolled', function () {
    return view('learner.enrolled');
})->name('learner.enrolled');

Route::get('/learner/activity', function () {
    return view('learner.activity');
})->name('learner.activity');

Route::get('/learner/course', function () {
    return view('learner.course');
})->name('learner.course');

Route::get('/learner/activitytest', function () {
    return view('learner.activitytest');
})->name('learner.activitytest');

Route::get('/learner/submission', function () {
    return view('learner.submission');
})->name('learner.submission');

Route::get('/learner/assessment', function () {
    return view('learner.assessment');
})->name('learner.assessment');

Route::get('/learner/evaluation', function () {
    return view('learner.evaluation');
})->name('learner.evaluation');


// End //