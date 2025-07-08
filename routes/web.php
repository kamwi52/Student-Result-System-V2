<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import all our controllers at the top
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes provided by Laravel/Breeze/UI (depending on your setup)
// Ensure you run 'php artisan ui vue --auth' or similar if using Laravel UI
// or if you installed Breeze via Composer and ran 'php artisan breeze:install'
Auth::routes();

// The default /home route after authentication (often redirects based on role)
Route::get('/home', [HomeController::class, 'index'])->name('home');


// === APPLICATION ROUTES ===

// The "Smart Dashboard" - our single entry point after login
// This route will check the user's role and redirect them.
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Redirect to Admin User Management (or another admin default)
        // Ensure admin.users.index route is defined in your admin group
        return redirect()->route('admin.users.index');
    }

    if ($user->role === 'teacher') {
        // Redirect to Teacher Dashboard
        // Ensure teacher.dashboard route is defined in your teacher group
        return redirect()->route('teacher.dashboard');
    }

    // Default fallback for students or other roles
    // This uses the generic 'dashboard' view (resources/views/dashboard.blade.php)
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


// General Authenticated User Routes (Profile)
// These routes are protected by the 'auth' middleware.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Admin Routes
// Protected by 'auth' and 'is.admin' middleware, prefixed with '/admin', and names prefixed with 'admin.'
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to the user list by default
    // This makes '/admin' act as the default admin landing page
    Route::get('/', function() { return redirect()->route('admin.users.index'); })->name('dashboard');

    // Custom, specific routes MUST be defined BEFORE the general resource route.
    // This ensures Laravel matches these specific paths before the more general resource paths.

    // Route to show the user import form
    // Accessible via route('admin.users.import.show') -> /admin/users/import
    Route::get('/users/import', [UserController::class, 'showImportForm'])->name('users.import.show');

    // Route to handle the POST request for importing users
    // This is the route the form in admin.users.import.blade.php submits to.
    // Accessible via route('admin.users.handleImport') -> /admin/users/import (POST)
    // === CORRECTED ROUTE NAME TO MATCH THE VIEW ===
    Route::post('/users/import', [UserController::class, 'handleImport'])->name('users.handleImport');
    // ==============================================

    // Routes for enrolling students in a specific class section
    // Accessible via route('admin.classes.enroll.index', $classSection) -> /admin/classes/{classSection}/enroll (GET)
    // Accessible via route('admin.classes.enroll.store', $classSection) -> /admin/classes/{classSection}/enroll (POST)
    Route::get('classes/{classSection}/enroll', [EnrollmentController::class, 'index'])->name('classes.enroll.index');
    Route::post('classes/{classSection}/enroll', [EnrollmentController::class, 'store'])->name('classes.enroll.store');


    // General "Resource" routes come last.
    // These define standard CRUD routes (index, create, store, show, edit, update, destroy).
    // Examples:
    // GET  /admin/users        -> admin.users.index
    // GET  /admin/users/create -> admin.users.create
    // POST /admin/users        -> admin.users.store
    // GET  /admin/users/{user} -> admin.users.show (if implemented)
    // GET  /admin/users/{user}/edit -> admin.users.edit
    // PUT/PATCH /admin/users/{user} -> admin.users.update
    // DELETE /admin/users/{user} -> admin.users.destroy
    Route::resource('users', UserController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassSectionController::class); // Assuming this manages ClassSections

});

// Teacher Routes
// Protected by 'auth' and 'is.teacher' middleware, prefixed with '/teacher', and names prefixed with 'teacher.'
Route::middleware(['auth', 'is.teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Teacher Dashboard route
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    // Grade entry routes and other teacher-specific routes will go here
});

// Student Routes (if any specific student routes are needed beyond the default dashboard)
// Example:
// Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
//     Route::get('/results', [StudentController::class, 'showResults'])->name('results');
// });