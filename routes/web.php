<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all the necessary controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradebookController;
use App\Http\Controllers\ReportCardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome route
Route::get('/', function () {
    return view('welcome');
});

// MODIFICATION: The main dashboard route now acts as a smart redirector.
// After a user logs in, this route checks their role and sends them to the correct dashboard.
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        // For admins, the default dashboard will be the user management page.
        return redirect()->route('admin.users.index');
    }

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }

    // You can add a redirect for students here later, e.g.:
    // if ($user->role === 'student') {
    //     return redirect()->route('student.dashboard');
    // }

    // Fallback for any other case or for students for now
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


// MODIFICATION: All your protected routes are now inside this main 'auth' group.
Route::middleware('auth')->group(function () {

    // --- BREEZE PROFILE ROUTES (Keep these) ---
    // These routes are provided by Breeze for managing user profiles.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- ADMIN ROUTES (Your custom logic) ---
    // These routes are only accessible to logged-in users with the 'admin' role.
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('subjects', SubjectController::class);
        Route::resource('classes', ClassSectionController::class);
        Route::resource('users', UserController::class);
        Route::resource('assessments', AssessmentController::class);

        Route::post('/assessments/import', [AssessmentController::class, 'import'])->name('assessments.import');
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    });


    // --- TEACHER ROUTES (Your custom logic) ---
    // These routes are only accessible to logged-in users with the 'teacher' role.
    Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/gradebook/{class}', [GradebookController::class, 'edit'])->name('gradebook.edit');
        Route::post('/gradebook/{class}', [GradebookController::class, 'store'])->name('gradebook.store');
    });


    // --- GENERAL AUTHENTICATED ROUTES ---
    // This route can be accessed by any logged-in user (admin, teacher, etc.).
    Route::get('/report-card/class/{class}/student/{student}/download', [ReportCardController::class, 'download'])->name('report-card.download');

});


// ESSENTIAL: This line loads all the Breeze authentication routes
// (login, register, forgot password, etc.). It MUST be kept.
require __DIR__.'/auth.php';