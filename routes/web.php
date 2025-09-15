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

// Middleware
use App\Http\Middleware\IsStudent;

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

    // =========================================================================
    // === THE DEFINITIVE FIX FOR THE 404 DOWNLOAD ERROR IS HERE ==============
    // =========================================================================
    Route::get('/reports/download-generated-file', function(Request $request) {
        // 1. Validate the secure signature to prevent unauthorized access.
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired download link.');
        }
        
        // 2. Get the file path from the URL.
        $filePath = $request->query('filename');

        // 3. Check if the file exists in the secure 'private' storage disk.
        if (Storage::disk('private')->exists($filePath)) {
            // 4. Return the file as a download response. This is the most reliable method.
            return Storage::disk('private')->download($filePath);
        }

        // 5. If the file is not found, provide a clear error message.
        abort(404, 'File not found. It may have been removed or the generation failed.');
        
    })->name('reports.download.generated');
});

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/downloads/users-template', [DashboardController::class, 'downloadUsersTemplate'])->name('downloads.users-template');
    Route::get('/downloads/classes-template', [DashboardController::class, 'downloadClassesTemplate'])->name('downloads.classes-template');
    Route::get('/downloads/subjects-template', [DashboardController::class, 'downloadSubjectsTemplate'])->name('downloads.subjects-template');
    Route::get('/downloads/results-template', [DashboardController::class, 'downloadResultsTemplate'])->name('downloads.results-template');
    Route::get('/downloads/user-guide', [DashboardController::class, 'downloadUserGuide'])->name('downloads.user-guide');
    
    // Combined Import Page Route
    Route::get('/imports', [DashboardController::class, 'showImportPage'])->name('imports.show');
    
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    Route::resource('users', UserController::class);
    Route::delete('users/bulk/destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    
    Route::get('/subjects/import', [SubjectController::class, 'showImportForm'])->name('subjects.import.show');
    Route::post('/subjects/import', [SubjectController::class, 'handleImport'])->name('subjects.import.handle');
    Route::resource('subjects', SubjectController::class);

    Route::get('/classes/import', [ClassSectionController::class, 'showImportForm'])->name('classes.import.show');
    Route::post('/classes/import', [ClassSectionController::class, 'handleImport'])->name('classes.import.handle');
    
    Route::get('/classes/{classSection}/subjects', [ClassSectionController::class, 'getSubjectsJson'])->name('classes.subjects.json');
    Route::resource('classes', ClassSectionController::class)->parameters(['classes' => 'classSection']);

    Route::prefix('results/import')->name('results.import.')->group(function () {
        Route::get('/step-1', [AdminResultController::class, 'showImportStep1'])->name('show_step1');
        Route::post('/prepare-step-2', [AdminResultController::class, 'prepareImportStep2'])->name('prepare_step2');
        Route::get('/step-2/{classSection}', [AdminResultController::class, 'showImportStep2'])->name('show_step2');
        Route::post('/step-2', [AdminResultController::class, 'handleImport'])->name('handle');
       Route::get('/final-reports/print/{filename}', [FinalReportController::class, 'printReport'])
        ->name('final-reports.print')
        ->where('filename', '(.*)');
    
    });
    Route::resource('results', AdminResultController::class);

    Route::match(['get', 'post'], '/enrollments/bulk-manage', [EnrollmentController::class, 'showBulkManageForm'])->name('enrollments.bulk-manage.show');
    Route::post('/enrollments/bulk-save', [EnrollmentController::class, 'handleBulkManage'])->name('enrollments.bulk-manage.handle');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    Route::get('assessments/bulk-create', [AssessmentController::class, 'showBulkCreateForm'])->name('assessments.bulk-create.show');
    Route::post('assessments/bulk-create', [AssessmentController::class, 'handleBulkCreate'])->name('assessments.bulk-create.handle');
    Route::resource('assessments', AssessmentController::class);
    Route::resource('grading-scales', GradingScaleController::class);
    Route::resource('academic-sessions', AcademicSessionController::class);
    Route::resource('terms', TermController::class);
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
    Route::get('/gradebook/{classSection}/{subject}/edit', [GradebookController::class, 'findAndEditLatestAssessment'])->name('gradebook.find-and-edit');
    Route::get('/gradebook/{assessment}/results', [GradebookController::class, 'showResults'])->name('gradebook.results');
    Route::post('/gradebook/{assessment}/results', [GradebookController::class, 'storeResults'])->name('gradebook.results.store');
    Route::get('/gradebook/{classSection}/{subject}', [GradebookController::class, 'showAssessments'])->name('gradebook.assessments');
    Route::post('/gradebook/{assessment}/results/import', [GradebookController::class, 'handleResultsImport'])->name('gradebook.results.import');
    Route::get('/gradebook/{assessment}/summary/print', [GradebookController::class, 'printSummary'])->name('gradebook.summary.print');
});

// Student Routes
Route::middleware(['auth', IsStudent::class])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/results', [StudentDashboardController::class, 'showResults'])->name('class.results');
    Route::get('/my-report', [ReportCardController::class, 'generateForStudent'])->name('my.report');
});

# Final deployment to unify APP_KEY.