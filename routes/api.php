<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/createannouncement', [AnnouncementController::class, 'store']);
    Route::get('/showAnnouncements', [AnnouncementController::class, 'show']);
    Route::put('/deleteAnnouncement', [AnnouncementController::class, 'delete']);
    Route::put('/updateAnnouncement', [AnnouncementController::class, 'update']);
});
