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
    Route::get('users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('users', UserController::class);

    Route::get('subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    Route::resource('subjects', SubjectController::class);
    
    Route::get('/classes/{classSection}/subjects', [ClassSectionController::class, 'getSubjectsJson'])->name('classes.subjects.json');
    Route::get('classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    Route::get('classes/enroll/import', [EnrollmentController::class, 'showBulkEnrollForm'])->name('classes.enroll.import.show');
    Route::post('classes/enroll/import', [EnrollmentController::class, 'handleBulkEnroll'])->name('classes.enroll.import.handle');
    Route::get('classes/post-test', [ClassSectionController::class, 'showPostTest'])->name('classes.showPostTest');
    Route::post('classes/post-test', [ClassSectionController::class, 'handlePostTest'])->name('classes.handlePostTest');
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);

    Route::match(['get', 'post'], '/enrollments/bulk-manage', [EnrollmentController::class, 'showBulkManageForm'])->name('enrollments.bulk-manage.show');
    Route::post('/enrollments/bulk-save', [EnrollmentController::class, 'handleBulkManage'])->name('enrollments.bulk-manage.handle');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    
    Route::get('assessments/bulk-create', [AssessmentController::class, 'showBulkCreateForm'])->name('assessments.bulk-create.show');
    Route::post('assessments/bulk-create', [AssessmentController::class, 'handleBulkCreate'])->name('assessments.bulk-create.handle');
    Route::get('assessments/import', [AssessmentController::class, 'showImportForm'])->name('assessments.import.show');
    Route::post('assessments/import', [AssessmentController::class, 'handleImport'])->name('assessments.import.handle');
    Route::resource('assessments', AssessmentController::class);

    Route::prefix('results/import')->name('results.import.')->group(function() {
        Route::get('/step-1', [AdminResultController::class, 'showImportStep1'])->name('show_step1');
        Route::post('/prepare-step-2', [AdminResultController::class, 'prepareImportStep2'])->name('prepare_step2');
        Route::post('/handle', [AdminResultController::class, 'handleImport'])->name('handle');
    });
    Route::resource('results', AdminResultController::class);
    
    // Settings Routes
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    Route::resource('terms', TermController::class);
    
    // Reporting Routes
    Route::get('/reports/{classSection}/assessments', [ReportingController::class, 'showAssessments'])->name('reports.show-assessments');
    Route::get('/reports/assessments/{assessment}/results', [ReportingController::class, 'showResults'])->name('reports.show-results');
    Route::post('/reports/generate-bulk', [ReportingController::class, 'generateBulkReport'])->name('reports.generate-bulk');
    Route::get('/reports/download', [ReportingController::class, 'downloadReport'])->middleware('signed')->name('reports.download');

    Route::prefix('final-reports')->name('final-reports.')->group(function() {
        Route::get('/', [FinalReportController::class, 'index'])->name('index');
        Route::get('/show-students', [FinalReportController::class, 'showStudents'])->name('show-students');
        Route::post('/generate', [FinalReportController::class, 'generate'])->name('generate');
        Route::get('/generate-single/{student_id}/{class_id}/{term_id}', [FinalReportController::class, 'generateSingle'])->name('generate-single');
    });

    // Notification and Download Routes
    Route::get('/notifications/{notification}', function (DatabaseNotification $notification) {
        $notification->markAsRead();
        if (isset($notification->data['action_url']) && $notification->data['action_url']) {
            return redirect($notification->data['action_url']);
        }
        return redirect()->back();
    })->name('notifications.show');

    Route::get('/reports/download-generated-file', function(Request $request) {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $filePath = $request->query('filename');
        if (Storage::disk('private')->exists($filePath)) {
            return Storage::disk('private')->download($filePath);
        }
        abort(404, 'File not found or link has expired.');
    })->name('reports.download.generated');

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