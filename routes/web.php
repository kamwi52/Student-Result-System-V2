<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReportCardController;

// Admin Controllers
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\Admin\FinalReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\GradingScaleController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\TermController;

// Teacher Controllers
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\BulkGradeController;
use App\Http\Controllers\Teacher\GradebookController;
use App\Http\Controllers\Teacher\ResultController as TeacherResultController;
use App\Http\Controllers\Teacher\AssignmentController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;

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
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.users.index'); })->name('dashboard');
    
    // Management Routes
    // Specific routes must come BEFORE the general resource route.
    Route::get('users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('users', UserController::class);

    Route::resource('subjects', SubjectController::class);
    
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    // Optional Import Routes (buttons removed from view)
    // Route::get('classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    // Route::post('classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    
    Route::resource('assessments', AssessmentController::class);
    Route::resource('results', AdminResultController::class);
    
    // Settings Routes
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);

    // ========================================================================
    // --- NEW: TERM MANAGEMENT ROUTE ---
    // This creates all necessary routes (index, create, store, edit, etc.) for managing terms.
    // ========================================================================
    Route::resource('terms', TermController::class);
    
    // --- SINGLE ASSESSMENT REPORTING WORKFLOW (Teacher-style) ---
    Route::get('/reports/{classSection}/assessments', [ReportingController::class, 'showAssessments'])->name('reports.show-assessments');
    Route::get('/reports/assessments/{assessment}/results', [ReportingController::class, 'showResults'])->name('reports.show-results');
    Route::post('/reports/generate-bulk', [ReportingController::class, 'generateBulkReport'])->name('reports.generate-bulk');
    Route::get('/reports/download', [ReportingController::class, 'downloadReport'])->middleware('signed')->name('reports.download');


    // --- COMPREHENSIVE RANKED REPORT CARD WORKFLOW ---
    Route::prefix('final-reports')->name('final-reports.')->group(function() {
        Route::get('/', [FinalReportController::class, 'index'])->name('index');
        Route::get('/show-students', [FinalReportController::class, 'showStudents'])->name('show-students');
        Route::post('/generate', [FinalReportController::class, 'generate'])->name('generate');
    });

});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');
    Route::post('/reports/generate-bulk', [ReportCardController::class, 'generateBulkForTeacher'])->name('reports.generate-bulk');
    Route::get('gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    Route::get('assignments/{assignment}/results', [GradebookController::class, 'showResults'])->name('assignments.results');
});

// Student Routes
Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('my.report');
});