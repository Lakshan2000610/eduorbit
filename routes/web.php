<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\SubjectSelectionController;

require __DIR__.'/auth.php';

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

    // add this route
    Route::get('/roadmaps/subjects/{grade}/{language}', [RoadmapController::class, 'subjectsIndex'])
        ->name('roadmaps.subjects.index');

    // Edit / Update Subject
    Route::get('/roadmaps/subject/{subject}/edit', [RoadmapController::class, 'editSubject'])
        ->name('roadmaps.edit-subject');
    Route::post('/roadmaps/subject/{subject}', [RoadmapController::class, 'updateSubject'])
        ->name('roadmaps.update-subject');

    // Topic edit / update / delete
    Route::get('/roadmaps/topic/{topic}/edit', [RoadmapController::class, 'editTopic'])->name('roadmaps.edit-topic');
    Route::post('/roadmaps/topic/{topic}', [RoadmapController::class, 'updateTopic'])->name('roadmaps.update-topic');
    Route::delete('/roadmaps/topic/{topic}', [RoadmapController::class, 'deleteTopic'])->name('roadmaps.delete-topic');

    // Subtopics list / edit / update / delete
    Route::get('/roadmaps/topic/{topic}/subtopics', [RoadmapController::class, 'subtopicsIndex'])->name('roadmaps.subtopics.index');
    Route::get('/roadmaps/subtopic/{subtopic}/edit', [RoadmapController::class, 'editSubtopic'])->name('roadmaps.edit-subtopic');
    Route::post('/roadmaps/subtopic/{subtopic}', [RoadmapController::class, 'updateSubtopic'])->name('roadmaps.update-subtopic');
    Route::delete('/roadmaps/subtopic/{subtopic}', [RoadmapController::class, 'deleteSubtopic'])->name('roadmaps.delete-subtopic');
});



// TEACHER ROUTES
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');
});

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Step 1 - grade/language form
    Route::get('/select-subjects', [SubjectSelectionController::class, 'showGradeForm'])->name('select-subjects');

    // preview subjects (POST from grade form)
    Route::post('/select-subjects/preview', [SubjectSelectionController::class, 'previewSubjects'])->name('select-subjects.preview');

    // final store
    Route::post('/select-subjects', [SubjectSelectionController::class, 'storeSelection'])->name('store-subjects');

    // show current selected subjects / roadmap
    Route::get('/selected-subjects', [SubjectSelectionController::class, 'showCurrentSelection'])->name('selected-subjects');

    // optional: history
    Route::get('/my-selections', [SubjectSelectionController::class, 'mySelections'])->name('my-selections');
});