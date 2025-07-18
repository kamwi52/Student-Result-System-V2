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
use App\Http\Controllers\Admin\AcademicSessionController; // <-- ADD THIS IMPORT

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

    // --- Specific Non-Resourceful Routes ---

    // Import Routes
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.handleImport');
    
    Route::get('/subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('/subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    
    Route::get('/classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('/classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    
    Route::get('/assessments/import', [AssessmentController::class, 'showImportForm'])->name('assessments.import.show');
    Route::post('/assessments/import', [AssessmentController::class, 'handleImport'])->name('assessments.import.handle');

    Route::get('/results/import/step-1', [AdminResultController::class, 'showImportStep1'])->name('results.import.step1');
    Route::get('/results/import/step-2', [AdminResultController::class, 'showImportStep2'])->name('results.import.step2');
    Route::post('/results/import/handle', [AdminResultController::class, 'handleImport'])->name('results.import.handle');

    // Other Specific Routes
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');

    // --- Resourceful Routes ---
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);
    Route::resource('assessments', AssessmentController::class);
    Route::resource('results', AdminResultController::class);
    Route::resource('grading-scales', GradingScaleController::class);
    
    // Academic Session Routes (ADD THIS LINE)
    Route::resource('academic-sessions', AcademicSessionController::class); 
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // === UPDATED GRADEBOOK ROUTES ===

    // Main Gradebook page: Shows list of Class-Subject pairs the teacher teaches
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook.index');
    
    // Page to view assignments for a specific Class & Subject combo
    // Parameters are ClassSection and Subject, not Assignment directly
    Route::get('gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    
    // Page to view results/grades for a specific assignment and assessment template
    // This route remains as is, but the link TO it from showAssessments will change.
    Route::get('gradebook/assignments/{assignment}/assessments/{assessment}', [GradebookController::class, 'showResults'])->name('gradebook.results');


    Route::get('/assignments/{assignment}/assessment/{assessment}/bulk-edit', [BulkGradeController::class, 'show'])->name('grades.bulk.show');
    Route::post('/grades/bulk/store', [BulkGradeController::class, 'store'])->name('grades.bulk.store');
    Route::get('/assignments/{assignment}/results/{result}/edit', [TeacherResultController::class, 'edit'])->name('results.edit');
    Route::put('/results/{result}', [TeacherResultController::class, 'update'])->name('results.update');
});

// Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
});