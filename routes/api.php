<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDependencyController;

// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
// });
// Route::middleware(['auth:sanctum','role:manager'])->group(function () {
//     Route::post('/tasks', [TaskController::class,'store']);
//     Route::put('/tasks/{task}', [TaskController::class,'update']);
// });



Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // عام (Manager + User)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // Manager فقط
    Route::middleware('role:manager')->group(function () {
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

        Route::post('/tasks/{task}/dependencies', [TaskDependencyController::class, 'store']);
        Route::delete('/tasks/{task}/dependencies/{dependency}', [TaskDependencyController::class, 'destroy']);
    });
});

