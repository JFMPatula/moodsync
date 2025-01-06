<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\MoodAnalyticsController;
use App\Http\Controllers\TodoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
        ]);
    })->name('dashboard');

    // Mood Tracking Routes
    Route::get('/mood-entries', function () {
        return Inertia::render('MoodEntries');
    })->name('mood-entries');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Analytics routes
    Route::get('/mood-analytics', [MoodAnalyticsController::class, 'index'])->name('mood.analytics');
    
    // API Routes
    // Route::prefix('api')->group(function () {
        // Mood entries
        Route::get('/api/mood-entries', [MoodEntryController::class, 'index']);
        Route::post('/api/mood-entry', [MoodEntryController::class, 'store']);
        Route::get('/mood-analytics', [MoodAnalyticsController::class, 'getAnalytics']);
        
        // Todo routes
        Route::get('/api/todos', [TodoController::class, 'index']);
        Route::post('/api/todos', [TodoController::class, 'store']);
        Route::put('/api/todos/{todo}', [TodoController::class, 'update']);
        Route::delete('/api/todos/{todo}', [TodoController::class, 'destroy']);
    // });
});

// Include Auth Routes
require __DIR__ . '/auth.php';
