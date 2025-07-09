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
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController; // <-- ADD THIS
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;

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

// The "Smart Dashboard" - our single entry point after login
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.users.index');
    }

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }

    // === MODIFIED: Redirect students to their own dashboard ===
    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }
    // ==========================================================

    // Default fallback for any other roles or if a student dashboard doesn't exist
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


// General Authenticated User Routes (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});


// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... (all your existing admin routes are fine)
    Route::get('/', function() { return redirect()->route('admin.users.index'); })->name('dashboard');
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.handleImport');
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassSectionController::class);
    Route::resource('assessments', AssessmentController::class);
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // ... (all your existing teacher routes are fine)
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classes/{classSection}/grades', [GradeController::class, 'enterGrades'])->name('grades.enter');
    Route::post('/classes/{classSection}/grades', [GradeController::class, 'storeGrades'])->name('grades.store');
});

// === ADDED: Student Routes ===
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    // We can use a general 'auth' middleware, as a student should only ever see their own data.
    // If you wanted extra security, you could create an 'is.student' middleware.
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});
// =============================