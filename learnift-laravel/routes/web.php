<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ── Public pages ──────────────────────────────────────────────────────────────
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// ── Guest-only (redirect to dashboard if already logged in) ───────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard — redirects to role-specific view
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards
    Route::get('/dashboard/admin',      [DashboardController::class, 'admin'])
         ->middleware('role:admin')
         ->name('dashboard.admin');

    Route::get('/dashboard/instructor', [DashboardController::class, 'instructor'])
         ->middleware('role:instructor,admin')
         ->name('dashboard.instructor');

    Route::get('/dashboard/student',    [DashboardController::class, 'student'])
         ->middleware('role:student')
         ->name('dashboard.student');

    // Course enrollment (students only)
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])
         ->middleware('role:student')
         ->name('courses.enroll');

    // Course management (instructors / admin)
    Route::middleware('role:instructor,admin')->group(function () {
        Route::get('/instructor/courses/create',       [CourseController::class, 'create'])->name('courses.create');
        Route::post('/instructor/courses',             [CourseController::class, 'store'])->name('courses.store');
        Route::get('/instructor/courses/{course}/edit',[CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/instructor/courses/{course}',     [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/instructor/courses/{course}',  [CourseController::class, 'destroy'])->name('courses.destroy');
    });
});
