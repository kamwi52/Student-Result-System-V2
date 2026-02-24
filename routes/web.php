<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportCardController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
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
use App\Http\Controllers\Teacher\GradebookController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;

// Middleware
use App\Http\Middleware\IsStudent;

/*
|--------------------------------------------------------------------------
| Web Routes: The Definitive Master File
|--------------------------------------------------------------------------
*/

// Public and Authentication Routes
Route::get('/', fn() => view('welcome'));
Auth::routes();

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification Handling
    Route::get('/notifications/{notification}', function (DatabaseNotification $notification) {
        $notification->markAsRead();
        if (isset($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }
        return redirect()->back();
    })->name('notifications.show');

    // Secure File Download Route
    Route::get('/reports/download-generated-file', function(Request $request) {
        if (!$request->hasValidSignature()) { abort(401, 'Invalid or expired download link.'); }
        $filePath = $request->query('filename');
        if (Storage::disk('private')->exists($filePath)) { return Storage::disk('private')->download($filePath); }
        abort(404, 'File not found.');
    })->name('reports.download.generated');
});

// =========================================================================
// === ADMIN ROUTES ========================================================
// =========================================================================
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/imports', [DashboardController::class, 'showImportPage'])->name('imports.show');
    
    // Template Downloads
    Route::get('/downloads/users-template', [DashboardController::class, 'downloadUsersTemplate'])->name('downloads.users-template');
    Route::get('/downloads/classes-template', [DashboardController::class, 'downloadClassesTemplate'])->name('downloads.classes-template');
    Route::get('/downloads/subjects-template', [DashboardController::class, 'downloadSubjectsTemplate'])->name('downloads.subjects-template');
    Route::get('/downloads/results-template', [DashboardController::class, 'downloadResultsTemplate'])->name('downloads.results-template');
    Route::get('/downloads/user-guide', [DashboardController::class, 'downloadUserGuide'])->name('downloads.user-guide');
    
    // =========================================================================
    // === THE DEFINITIVE FIX: CUSTOM ROUTES ARE DEFINED BEFORE RESOURCE ROUTES
    // =========================================================================
    
    // Custom User Routes
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::delete('users/bulk/destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    
    // Custom Class Routes
    Route::post('/classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::get('/classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::get('/classes/{classSection}/subjects', [ClassSectionController::class, 'getSubjectsJson'])->name('classes.subjects.json');

    // Custom Subject Routes
    Route::post('/subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    Route::get('/subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');

    // Custom Assessment Routes (Defined before the resource route)
    Route::get('assessments/bulk-create', [AssessmentController::class, 'showBulkCreateForm'])->name('assessments.bulk-create.show');
    Route::post('assessments/bulk-create', [AssessmentController::class, 'handleBulkCreate'])->name('assessments.bulk-create.handle');
    
    // Resourceful CRUD Controllers (Defined AFTER custom routes to prevent conflicts)
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);
        Route::get('results/ranked', [AdminResultController::class, 'ranked'])->name('results.ranked');
    Route::resource('results', AdminResultController::class);
    Route::resource('assessments', AssessmentController::class);
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    Route::resource('terms', TermController::class);

    // Results Import Workflow
    Route::prefix('results/import')->name('results.import.')->group(function () {
        Route::get('/step-1', [AdminResultController::class, 'showImportStep1'])->name('show_step1');
        Route::post('/prepare-step-2', [AdminResultController::class, 'prepareImportStep2'])->name('prepare_step2');
        Route::get('/step-2/{classSection}', [AdminResultController::class, 'showImportStep2'])->name('show_step2');
        Route::post('/step-2', [AdminResultController::class, 'handleImport'])->name('handle');
    });

    // Final Reports Workflow
    Route::prefix('final-reports')->name('final-reports.')->group(function() {
        Route::get('/', [FinalReportController::class, 'index'])->name('index');
        Route::get('/show-students', [FinalReportController::class, 'showStudents'])->name('show-students');
        Route::post('/generate', [FinalReportController::class, 'generate'])->name('generate');
        Route::get('/generate-single/{student_id}/{class_id}/{term_id}', [FinalReportController::class, 'generateSingle'])->name('generate-single');
        Route::get('/print/{filename}', [FinalReportController::class, 'printReport'])->name('print')->where('filename', '(.*)');
    });

    // Other Custom Functionality Routes
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    Route::match(['get', 'post'], '/enrollments/bulk-manage', [EnrollmentController::class, 'showBulkManageForm'])->name('enrollments.bulk-manage.show');
    Route::post('/enrollments/bulk-save', [EnrollmentController::class, 'handleBulkManage'])->name('enrollments.bulk-manage.handle');
});

// =========================================================================
// === TEACHER ROUTES ======================================================
// =========================================================================
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/gradebook/{classSection}/{subject}/edit', [GradebookController::class, 'findAndEditLatestAssessment'])->name('gradebook.find-and-edit');
    Route::get('/gradebook/{assessment}/results', [GradebookController::class, 'showResults'])->name('gradebook.results');
    Route::post('/gradebook/{assessment}/results', [GradebookController::class, 'storeResults'])->name('gradebook.results.store');
    Route::get('/gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    Route::post('/gradebook/{assessment}/results/import', [GradebookController::class, 'handleResultsImport'])->name('gradebook.results.import');
    Route::get('/gradebook/{assessment}/summary/print', [GradebookController::class, 'printSummary'])->name('gradebook.summary.print');
});

// =========================================================================
// === STUDENT ROUTES ======================================================
// =========================================================================
Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('my.report');
});