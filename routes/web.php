<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReportCardController;

// Admin Controllers
use App\Http\Controllers\Admin\ReportingController; // <-- ADD THIS
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\GradingScaleController;
use App\Http\Controllers\Admin\AcademicSessionController;

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
    Route::resource('users', UserController::class);
    Route::get('users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('subjects', SubjectController::class);
    Route::get('subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);
    Route::get('classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    Route::resource('assessments', AssessmentController::class);
    Route::get('results/import/step-1', [AdminResultController::class, 'showImportStep1'])->name('results.import.step1');
    Route::post('results/import/step-2', [AdminResultController::class, 'showImportStep2'])->name('results.import.step2');
    Route::post('results/import/process', [AdminResultController::class, 'handleImport'])->name('results.import.process');
    Route::resource('results', AdminResultController::class);
    
    // Settings Routes
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    
    // --- REPORTING ROUTES FOR ADMIN ---
    Route::get('/reports', [ReportingController::class, 'index'])->name('reports.index'); // <-- ADD THIS
    Route::get('/students/{student}/report', [ReportCardController::class, 'generateForAdmin'])->name('students.report');
    Route::get('/class-sections/{classSection}/report', [ReportCardController::class, 'generateForClass'])->name('class-sections.report');
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');
    Route::get('gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    Route::get('assignments/{assignment}/results', [GradebookController::class, 'showResults'])->name('assignments.results');
    Route::get('/assignments/{assignment}/bulk-edit', [BulkGradeController::class, 'edit'])->name('grades.bulk-edit');
    Route::put('/assignments/{assignment}/bulk-update', [BulkGradeController::class, 'update'])->name('grades.bulk-update');
    Route::get('/results/{result}/edit', [TeacherResultController::class, 'edit'])->name('results.edit');
    Route::put('/results/{result}', [TeacherResultController::class, 'update'])->name('results.update');
    Route::get('/students/{student}/report', [ReportCardController::class, 'generateForTeacher'])->name('students.report');
    Route::get('/class-sections/{classSection}/report', [ReportCardController::class, 'generateForClass'])->name('class-section.report');
});

// Student Routes
Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('my.report');
});