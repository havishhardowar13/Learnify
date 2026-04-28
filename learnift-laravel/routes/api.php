<?php

use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\EnrollmentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are prefixed with /api and are stateless (token auth).
| Test with Postman or curl:
|   curl http://localhost/learnify-laravel/public/api/courses
*/

// ── Public endpoints (no auth required) ──────────────────────────────────────
Route::get('/courses',          [CourseApiController::class, 'index']);
Route::get('/courses/{course}', [CourseApiController::class, 'show']);

// ── Protected endpoints (requires Sanctum token) ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'id'         => $request->user()->id,
            'first_name' => $request->user()->first_name,
            'last_name'  => $request->user()->last_name,
            'email'      => $request->user()->email,
            'role'       => $request->user()->role,
        ]);
    });

    // Enrollments
    Route::get('/enrollments',                      [EnrollmentApiController::class, 'index']);
    Route::post('/courses/{course}/enroll',         [EnrollmentApiController::class, 'store']);
    Route::delete('/courses/{course}/unenroll',     [EnrollmentApiController::class, 'destroy']);
    Route::patch('/enrollments/{enrollment}/progress', [EnrollmentApiController::class, 'updateProgress']);

    // Course CRUD (instructor / admin only)
    Route::post('/courses',           [CourseApiController::class, 'store']);
    Route::put('/courses/{course}',   [CourseApiController::class, 'update']);
    Route::delete('/courses/{course}',[CourseApiController::class, 'destroy']);
});
