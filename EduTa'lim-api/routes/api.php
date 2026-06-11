<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Teacher\CourseController;
use App\Http\Controllers\Api\V1\Student\EnrollmentController;
use App\Http\Controllers\Api\V1\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Api\V1\Student\TaskController as StudentTaskController;
use App\Http\Controllers\Api\V1\Teacher\TaskController;
use App\Http\Controllers\Api\V1\UploadController;
use App\Http\Controllers\Api\V1\Teacher\LessonController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Auth ──────────────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout',  [AuthController::class, 'logout']);
            Route::get('profile',  [AuthController::class, 'profile']);
        });
    });

    // ── Public courses (read-only) ────────────────────────────────────────────
    Route::get('courses',          [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);

    // ── Lesson view (free=public, paid=enrollment/teacher/admin required) ────
    Route::get('courses/{course}/lessons/{lesson}', [StudentLessonController::class, 'show']);

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('admin/statistics',   [DashboardController::class,    'statistics']);
        Route::get('admin/users',        [UserController::class,         'index']);
        Route::get('admin/enrollments',  [AdminEnrollmentController::class, 'index']);
        Route::apiResource('categories', CategoryController::class);
    });

    // ── Admin / Teacher ───────────────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'role:admin,teacher'])->group(function () {
        Route::post('courses',             [CourseController::class, 'store']);
        Route::put('courses/{course}',     [CourseController::class, 'update']);
        Route::patch('courses/{course}',   [CourseController::class, 'update']);
        Route::delete('courses/{course}',  [CourseController::class, 'destroy']);
        Route::apiResource('courses.lessons', LessonController::class)->shallow();

        // Tasks
        Route::get('tasks',                                            [TaskController::class, 'index']);
        Route::post('tasks',                                           [TaskController::class, 'store']);
        Route::put('tasks/{task}',                                     [TaskController::class, 'update']);
        Route::delete('tasks/{task}',                                  [TaskController::class, 'destroy']);
        Route::get('tasks/{task}/submissions',                         [TaskController::class, 'submissions']);
        Route::patch('tasks/{task}/submissions/{submission}/review',   [TaskController::class, 'review']);
    });

    Route::middleware(['auth:sanctum', 'role:admin,teacher'])->prefix('upload')->group(function () {
        Route::post('courses/{course}/thumbnail', [UploadController::class, 'thumbnail']);
        Route::post('avatar',                     [UploadController::class, 'avatar']);
    });

    // ── Student ───────────────────────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
        Route::post('courses/{course}/enroll',   [EnrollmentController::class, 'enroll']);
        Route::delete('courses/{course}/enroll', [EnrollmentController::class, 'unenroll']);
        Route::get('my-courses',                 [EnrollmentController::class, 'myCourses']);
        Route::get('my-tasks',                   [StudentTaskController::class, 'index']);
        Route::post('tasks/{task}/submit',        [StudentTaskController::class, 'submit']);
    });

});
