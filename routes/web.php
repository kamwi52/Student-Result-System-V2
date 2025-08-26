<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;

// Import all controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReportCardController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
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
use App\Http\Controllers\Teacher\ReportCardController as TeacherReportCardController;

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

    Route::get('/notifications/{notification}', function (DatabaseNotification $notification) {
        $notification->markAsRead();
        if (isset($notification->data['action_url']) && $notification->data['action_url']) {
            return redirect($notification->data['action_url']);
        }
        return redirect()->back();
    })->name('notifications.show');

    Route::get('/reports/download-generated-file', function(Request $request) {
        if (!$request->hasValidSignature()) { abort(401, 'Invalid or expired download link.'); }
        $filePath = $request->query('filename');
        if (Storage::disk('private')->exists($filePath)) { return Storage::disk('private')->download($filePath); }
        abort(404, 'File not found or has been removed.');
    })->name('reports.download.generated');
});

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    // === FIX: The admin dashboard route now points to the new DashboardController ===
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('users', UserController::class);

    // Subject Management
    Route::resource('subjects', SubjectController::class);

    // Class Management
    Route::get('/classes/{classSection}/subjects', [ClassSectionController::class, 'getSubjectsJson'])->name('classes.subjects.json');
    Route::get('/classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('/classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);

    // Enrollment Management
    Route::match(['get', 'post'], '/enrollments/bulk-manage', [EnrollmentController::class, 'showBulkManageForm'])->name('enrollments.bulk-manage.show');
    Route::post('/enrollments/bulk-save', [EnrollmentController::class, 'handleBulkManage'])->name('enrollments.bulk-manage.handle');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');

    // Assessment Management
    Route::get('assessments/bulk-create', [AssessmentController::class, 'showBulkCreateForm'])->name('assessments.bulk-create.show');
    Route::post('assessments/bulk-create', [AssessmentController::class, 'handleBulkCreate'])->name('assessments.bulk-create.handle');
    Route::resource('assessments', AssessmentController::class);
    Route::resource('results', AdminResultController::class);

    // Settings Management
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    Route::resource('terms', TermController::class);

    // Reporting Management
    Route::prefix('final-reports')->name('final-reports.')->group(function() {
        Route::get('/', [FinalReportController::class, 'index'])->name('index');
        Route::get('/show-students', [FinalReportController::class, 'showStudents'])->name('show-students');
        Route::post('/generate', [FinalReportController::class, 'generate'])->name('generate');
        Route::get('/generate-single/{student_id}/{class_id}/{term_id}', [FinalReportController::class, 'generateSingle'])->name('generate-single');
    });
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // New Streamlined Gradebook Workflow
    Route::get('/gradebook/{classSection}/{subject}/edit', [GradebookController::class, 'findAndEditLatestAssessment'])->name('gradebook.find-and-edit');
    Route::get('/gradebook/{assessment}/results', [GradebookController::class, 'showResults'])->name('gradebook.results');
    Route::post('/gradebook/{assessment}/results', [GradebookController::class, 'storeResults'])->name('gradebook.results.store');
    
    // Other Gradebook Features
    Route::get('/gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    Route::post('/gradebook/{assessment}/results/import', [GradebookController::class, 'handleResultsImport'])->name('gradebook.results.import');
    Route::get('/gradebook/{assessment}/summary/print', [GradebookController::class, 'printSummary'])->name('gradebook.summary.print');
});

// Student Routes
Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('my.report');
});