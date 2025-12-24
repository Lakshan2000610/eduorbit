<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoadmapController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PricingManagementController;


use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\SubjectSelectionController;
use App\Http\Controllers\Student\SubjectProgressController;

use App\Http\Controllers\Teacher\TeacherDashboard;
use App\Http\Controllers\Teacher\GigController;
use App\Http\Controllers\Teacher\RequestController;
use App\Http\Controllers\Teacher\CalendarController;
use App\Http\Controllers\Teacher\EarningsController;
use App\Http\Controllers\Teacher\MessageController;
use App\Http\Controllers\Teacher\NotificationController;
use App\Http\Controllers\Teacher\SettingsController;
use App\Http\Controllers\Teacher\TeacherStudentController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('welcome'); // Laravel will now understand route('welcome')

// REMOVED: Route::get('/dashboard', function () { ... })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ADMIN ROUTES
Route::middleware(['web','auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Roadmaps
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

    Route::get('/pricing-management', [PricingManagementController::class, 'index'])->name('pricing-management.index');
    
    Route::post('/subtopic-pricing', [PricingManagementController::class, 'updateSubtopicPricing'])->name('subtopic-pricing.update');
    Route::post('/platform-fee', [PricingManagementController::class, 'updatePlatformFee'])->name('platform-fee.update');
});



// routes/web.php or wherever you define your routes


// TEACHER ROUTES
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

    // My Gigs
    Route::get('/gigs', [GigController::class, 'index'])->name('gigs');
    Route::get('/gigs/create', [GigController::class, 'create'])->name('gigs.create');
    Route::post('/gigs', [GigController::class, 'store'])->name('gigs.store');
    Route::get('/gigs/{gig}/edit', [GigController::class, 'edit'])->name('gigs.edit');
    Route::put('/gigs/{gig}', [GigController::class, 'update'])->name('gigs.update');
    Route::patch('/gigs/{gig}/status', [GigController::class, 'updateStatus'])->name('gigs.update-status');
    
    // AJAX Routes
    Route::get('/gig-subjects', [GigController::class, 'getSubjects'])->name('gig-subjects');
    Route::get('/gig-topics', [GigController::class, 'getTopics'])->name('gig-topics');
    Route::get('/gig-subtopics', [GigController::class, 'getSubtopics'])->name('gig-subtopics');
    // Add this route to your routes file (web.php or teacher routes group)
    Route::get('/gigs/{gig}', [GigController::class, 'show'])->name('gigs.show');
    // Add this route to your routes file (in the teacher group)
    Route::delete('/gigs/{gig}', [GigController::class, 'destroy'])->name('gigs.destroy');
    Route::get('/gig-subtopic-pricing', [GigController::class, 'getSubtopicPricing'])->name('teacher.gig-subtopic-pricing');
    // ... other routes ...
    // Student Requests
    Route::get('/requests', [RequestController::class, 'index'])->name('requests');

    // My Students
    Route::get('/students', [TeacherStudentController::class, 'index'])->name('students');

    // Session Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Earnings & Payments
    Route::get('/earn', [EarningsController::class, 'index'])->name('earn');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Teacher Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

});

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Step 1 - grade/language form
    Route::get('/select-subjects', [SubjectSelectionController::class, 'showGradeForm'])->name('select-subjects');

    // preview subjects (POST from grade form)
    Route::post('/select-subjects/preview', [SubjectSelectionController::class, 'previewSubjects'])->name('select-subjects.preview');

    // Add safe GET that redirects back to the grade form (prevents 405 on direct GET)
    Route::get('/select-subjects/preview', function () {
        return redirect()->route('student.select-subjects');
    });

    // final store
    Route::post('/select-subjects', [SubjectSelectionController::class, 'storeSelection'])->name('store-subjects');

    // show current selected subjects / roadmap
    Route::get('/selected-subjects', [SubjectSelectionController::class, 'showCurrentSelection'])->name('selected-subjects');

    // optional: history
    Route::get('/my-selections', [SubjectSelectionController::class, 'mySelections'])->name('my-selections');

    // Add this new route for progress page
    Route::get('/progress/{subjectId}', [StudentDashboardController::class, 'showProgress'])->name('progress');

    // Progress update routes (MOVED HERE)
    Route::post('/subjects/{subject}/progress', [SubjectProgressController::class, 'update'])
        ->name('subject.progress.update');
    
    Route::post('/resource/{resource}/mark-complete', [SubjectProgressController::class, 'markResourceComplete'])
        ->name('resource.mark-complete');
});