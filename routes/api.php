<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AnalyticsController;

Route::middleware(['auth:sanctum'])->group(function () {
    // User route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Mood entries routes
    Route::post('/mood-entry', [MoodEntryController::class, 'store']);
    Route::get('/mood-entries', [MoodEntryController::class, 'index']);
    Route::get('/user-analytics', [AnalyticsController::class, 'getUserAnalytics']);

    // Todo routes
    Route::prefix('todos')->group(function () {
        Route::get('/', [TodoController::class, 'index']);
        Route::post('/', [TodoController::class, 'store']);
        Route::put('/{todo}', [TodoController::class, 'update']);
        Route::delete('/{todo}', [TodoController::class, 'destroy']);
    });
});
