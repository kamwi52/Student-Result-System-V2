<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all our controllers at the top
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\ResultController;
// use App\Http\Controllers\Admin\AssignmentController; // This remains commented out as the controller does not exist
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Teacher\BulkGradeController;
// === NEW CONTROLLER IMPORT ===
use App\Http\Controllers\Teacher\GradebookController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// === APPLICATION ROUTES ===
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') { return redirect()->route('admin.users.index'); }
    if ($user->role === 'teacher') { return redirect()->route('teacher.dashboard'); }
    if ($user->role === 'student') { return redirect()->route('student.dashboard'); }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.users.index'); })->name('dashboard');
    
    // --- Specific Routes First (To avoid conflicts with resource routes) ---

    // User Import
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.handleImport');
    
    // Enrollments
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    
    // Assessment Import/Export
    Route::get('/assessments/import', [AssessmentController::class, 'showImportForm'])->name('assessments.import.show');
    Route::post('/assessments/simple-import', [AssessmentController::class, 'handleSimpleImport'])->name('assessments.simpleImport.handle');
    Route::get('/assessments/export', [AssessmentController::class, 'export'])->name('assessments.export');
    
    // Subject Import
    Route::get('/subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('/subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');

    // Class Import & Testing
    Route::get('/classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('/classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::get('/classes/bare-minimum-test', [ClassSectionController::class, 'handleBareMinimumImport'])->name('classes.bareMinimumTest');

    // --- NEW NO-FILE POST TEST ROUTES ---
    Route::get('/classes/post-test', [ClassSectionController::class, 'showPostTest'])->name('classes.showPostTest');
    Route::post('/classes/post-test', [ClassSectionController::class, 'handlePostTest'])->name('classes.handlePostTest');

    // Result Import
    Route::get('/results/import', [ResultController::class, 'showImportForm'])->name('results.import.show');
    Route::post('/results/import', [ResultController::class, 'handleImport'])->name('results.import.handle');
    
    // --- Resourceful (General) Routes Last ---
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('assessments', AssessmentController::class);
    Route::resource('results', ResultController::class);

    // === THE FIX ===
    // We explicitly name the route parameter to match the controller's variable name.
    Route::resource('classes', ClassSectionController::class)->parameters([
        'classes' => 'classSection'
    ]);
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // === START: NEW GRADEBOOK ROUTES ===
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');

    // API routes for dynamic data loading, kept in 'web' middleware for auth
    // I am changing {classSection} to {schoolClass} to match your model name for clarity
    Route::get('api/classes/{schoolClass}/assessments', [GradebookController::class, 'getAssessmentsForClass'])->name('api.class.assessments');
    Route::get('api/classes/{schoolClass}/assessments/{assessment}/results', [GradebookController::class, 'getResults'])->name('api.class.assessment.results');
    // === END: NEW GRADEBOOK ROUTES ===
    // In routes/web.php, inside the Teacher Routes group...

// === START: REVISED, SIMPLER GRADEBOOK ROUTES ===

// Page 1: Show a list of the teacher's classes.
Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');

// Page 2: Show a list of assessments for a specific class.
Route::get('gradebook/classes/{classSection}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');

// Page 3: Show the final results for a specific class and assessment.
Route::get('gradebook/classes/{classSection}/assessments/{assessment}', [GradebookController::class, 'showResults'])->name('gradebook.results');

// === END: REVISED GRADEBOOK ROUTES ===

    // Import/Export Routes
    Route::get('/grades/import', [BulkGradeController::class, 'showImportForm'])->name('grades.import.show');
    Route::post('/grades/import', [BulkGradeController::class, 'handleImport'])->name('grades.import.handle');

    // Bulk Grade Entry Routes
    Route::get('/grades/bulk', [BulkGradeController::class, 'create'])->name('grades.bulk.create');
    Route::post('/grades/bulk/show', [BulkGradeController::class, 'show'])->name('grades.bulk.show');
    Route::post('/grades/bulk/store', [BulkGradeController::class, 'store'])->name('grades.bulk.store');
    
    // Legacy single-entry routes
    Route::get('/classes/{classSection}/grades', [GradeController::class, 'enterGrades'])->name('grades.enter');
    Route::post('/classes/{classSection}/grades', [GradeController::class, 'storeGrades'])->name('grades.store');
});

// Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});