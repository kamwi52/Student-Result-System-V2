<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (Login, Register, etc.)
Auth::routes();

// Default authenticated user route (for students)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// --- ADMIN ROUTES ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Resourceful routes for core management
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\ClassSectionController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('assessments', \App\Http\Controllers\Admin\AssessmentController::class);

    // Import route for assessments
    Route::post('/assessments/import', [\App\Http\Controllers\Admin\AssessmentController::class, 'import'])->name('assessments.import');

    // Enrollment routes
    Route::get('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'store'])->name('enrollments.store');
});


// --- TEACHER ROUTES ---
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Gradebook routes
    Route::get('/gradebook/{class}', [\App\Http\Controllers\Teacher\GradebookController::class, 'edit'])->name('gradebook.edit');
    Route::post('/gradebook/{class}', [\App\Http\Controllers\Teacher\GradebookController::class, 'store'])->name('gradebook.store');
});


// --- GENERAL AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {
    // PDF Report Card Route
    Route::get('/report-card/class/{class}/student/{student}/download', [\App\Http\Controllers\ReportCardController::class, 'download'])->name('report-card.download');
});