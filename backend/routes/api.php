<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// ── Auth (ochiq) ──────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ── Himoyalangan ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

    // Attendance
    Route::post('/attendance/scan', [AttendanceController::class, 'scan']);
    Route::post('/attendance/checkin', [AttendanceController::class, 'manualCheckin']);
    Route::post('/attendance/checkout', [AttendanceController::class, 'manualCheckout']);
    Route::get('/attendance/live', [AttendanceController::class, 'live']);
    Route::get('/attendance/report/daily', [AttendanceController::class, 'daily']);
    Route::get('/attendance/report/summary', [AttendanceController::class, 'summary']);
    Route::get('/attendance/stats', [AttendanceController::class, 'stats']);
    Route::get('/attendance/user/{userId}', [AttendanceController::class, 'byUser']);

    // Users (faqat admin)
    Route::apiResource('/users', UserController::class);
    Route::get('/users/{user}/qr', [UserController::class, 'qr']);
    Route::post('/users/{user}/qr/regenerate', [UserController::class, 'regenerateQr']);

    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/classes', ClassController::class);
});
