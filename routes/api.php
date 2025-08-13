<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/announcement', [AnnouncementController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('guest')->group(function () {
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/announcement/create', [AnnouncementController::class, 'store']);
    Route::get('/announcement/show', [AnnouncementController::class, 'show']);
    Route::put('/announcement/delete', [AnnouncementController::class, 'delete']);
    Route::put('/announcement/update', [AnnouncementController::class, 'update']);
});
