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
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class)->names('subjects');
    Route::resource('classes', \App\Http\Controllers\Admin\ClassSectionController::class)->names('classes');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('users');
    Route::resource('assessments', \App\Http\Controllers\Admin\AssessmentController::class)->names('assessments');

    Route::get('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'store'])->name('enrollments.store');
});


// --- TEACHER ROUTES ---
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // =========================================================
    //  THIS IS THE NEW ROUTE FOR THE GRADEBOOK
    // =========================================================
    // The {class} part is a route parameter for the Class ID
    Route::get('/gradebook/{class}', [\App\Http\Controllers\Teacher\GradebookController::class, 'index'])->name('gradebook.index');
    // We will also need a route to SAVE the grades later
    Route::post('/gradebook', [\App\Http\Controllers\Teacher\GradebookController::class, 'store'])->name('gradebook.store');
    // =========================================================
});
// In routes/web.php

// PDF Report Card Route
Route::middleware(['auth'])->group(function () {
    // We pass the Class and Student IDs to know which report to generate
    Route::get('/report-card/class/{class}/student/{student}/download', [\App\Http\Controllers\ReportCardController::class, 'download'])->name('report-card.download');
});