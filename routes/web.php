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
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\GradingScaleController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\BulkGradeController;
use App\Http\Controllers\Teacher\GradebookController;
use App\Http\Controllers\Teacher\ResultController as TeacherResultController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Teacher\AssignmentController;
use App\Http\Controllers\ReportCardController; // <-- ADD THIS LINE

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
    
    // User Routes
    Route::get('users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('users', UserController::class);

    // Subject Routes
    Route::get('subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    Route::resource('subjects', SubjectController::class);
    
    // Class Routes
    Route::get('classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);

    // Assessment Routes
    Route::resource('assessments', AssessmentController::class);

    // Result Routes
    Route::get('results/import/step-1', [AdminResultController::class, 'showImportStep1'])->name('results.import.step1');
    Route::post('results/import/step-2', [AdminResultController::class, 'showImportStep2'])->name('results.import.step2');
    Route::post('results/import/process', [AdminResultController::class, 'handleImport'])->name('results.import.process');
    Route::resource('results', AdminResultController::class);
    
    // Other Admin Routes
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    
    // --- REPORT CARD ROUTE (ADMIN) ---
    Route::get('/students/{student}/report', [ReportCardController::class, 'generateForAdmin'])->name('students.report');
});


// === TEACHER ROUTES ===
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Gradebook Navigation
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');
    Route::get('gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    
    // Assignment & Result Management
    Route::get('assignments/{assignment}/results', [GradebookController::class, 'showResults'])->name('assignments.results');
    Route::get('/assignments/{assignment}/bulk-edit', [BulkGradeController::class, 'edit'])->name('grades.bulk-edit');
    Route::put('/assignments/{assignment}/bulk-update', [BulkGradeController::class, 'update'])->name('grades.bulk-update');
    Route::get('/results/{result}/edit', [TeacherResultController::class, 'edit'])->name('results.edit');
    Route::put('/results/{result}', [TeacherResultController::class, 'update'])->name('results.update');

    // --- REPORT CARD ROUTE (TEACHER) ---
    Route::get('/students/{student}/report', [ReportCardController::class, 'generateForTeacher'])->name('students.report');
});


// === STUDENT ROUTES ===
Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    
    // --- REPORT CARD ROUTE (STUDENT) ---
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('report');
});