<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Admin\RoadmapController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;

Route::get('/', function () {
    return view('welcome');
});

// REMOVED: Route::get('/dashboard', function () { ... })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    Route::get('/roadmaps', [RoadmapController::class, 'index'])->name('roadmaps.index');
    Route::get('/roadmaps/create', [RoadmapController::class, 'create'])->name('roadmaps.create');
    Route::post('/roadmaps', [RoadmapController::class, 'store'])->name('roadmaps.store');
    Route::get('/roadmaps/add-subject', [RoadmapController::class, 'addSubject'])->name('roadmaps.add-subject');
    Route::post('/roadmaps/store-subject', [RoadmapController::class, 'storeSubject'])->name('roadmaps.store-subject');
    Route::get('/roadmaps/subject/{subjectId}/topics', [RoadmapController::class, 'manageTopics'])->name('roadmaps.manage-topics');
    Route::post('/roadmaps/subject/{subjectId}/topics', [RoadmapController::class, 'storeTopic'])->name('roadmaps.store-topic');
    Route::get('/roadmaps/topic/{topicId}/add-subtopic', [RoadmapController::class, 'addSubtopic'])->name('roadmaps.add-subtopic');
    Route::post('/roadmaps/topic/{topicId}/store-subtopic', [RoadmapController::class, 'storeSubtopic'])->name('roadmaps.store-subtopic');
    Route::get('/roadmaps/view/{grade}/{language}', [RoadmapController::class, 'view'])->name('roadmaps.view');
    Route::delete('/roadmaps/delete/{grade}/{language}', [RoadmapController::class, 'delete'])->name('roadmaps.delete');

    Route::get('/students', [StudentController::class, 'index'])->name('students');
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
});



// TEACHER ROUTES
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');
});

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';