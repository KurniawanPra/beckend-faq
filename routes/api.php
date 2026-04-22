<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\UserInquiryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — FAQ Portal PT INL
|--------------------------------------------------------------------------
|
| Public endpoints  : tidak memerlukan autentikasi
| Protected endpoints: menggunakan Sanctum (auth:sanctum)
|
*/

// ─── Public Routes ────────────────────────────────────────────────────────────

// Auth
Route::post('/login', [AuthController::class, 'login']);

// FAQ publik (untuk halaman utama pengunjung)
Route::get('/faqs', [FaqController::class, 'index']);

// User inquiry (form "Hubungi Kami" dari pengunjung)
Route::post('/user-inquiries', [UserInquiryController::class, 'store']);

// ─── Protected Routes (Admin) ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Topics
    Route::get('/topics', [TopicController::class, 'index']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::put('/topics/{id}', [TopicController::class, 'update']);
    Route::delete('/topics/{id}', [TopicController::class, 'destroy']);

    // Questions (Pertanyaan FAQ)
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::put('/questions/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    // User Inquiries (Admin view)
    Route::get('/user-inquiries', [UserInquiryController::class, 'index']);
    Route::patch('/user-inquiries/{id}/status', [UserInquiryController::class, 'updateStatus']);
    Route::delete('/user-inquiries/{id}', [UserInquiryController::class, 'destroy']);
});
