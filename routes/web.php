<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all our controllers at the top
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// The "Smart Dashboard" - our single entry point after login
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') { return redirect()->route('admin.users.index'); }
    if ($user->role === 'teacher') { return redirect()->route('teacher.dashboard'); }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// General Authenticated User Routes (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === APPLICATION-SPECIFIC ROUTES ===

// Admin Routes
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    // --- THIS IS THE CORRECTED SECTION ---
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.import.handle');
    // ------------------------------------

    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassSectionController::class);
    // Note: Enrollment routes would be added here
});

// Teacher Routes
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/class/{classSection}/grades', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/class/{classSection}/grades', [GradeController::class, 'store'])->name('grades.store');
});

// Include Laravel Breeze's authentication routes
require __DIR__.'/auth.php';