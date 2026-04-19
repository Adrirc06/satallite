<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BuildController;
use App\Http\Controllers\Api\V1\Components\ChassisController;
use App\Http\Controllers\Api\V1\Components\CpuController;
use App\Http\Controllers\Api\V1\Components\DriveController;
use App\Http\Controllers\Api\V1\Components\GpuController;
use App\Http\Controllers\Api\V1\Components\MotherboardController;
use App\Http\Controllers\Api\V1\Components\PsuController;
use App\Http\Controllers\Api\V1\Components\RamController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Auth Routes
    Route::post('/check-user', [AuthController::class, 'checkUser']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public Routes
    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    Route::get('/builds', [BuildController::class, 'index']);
    Route::get('/users/{user}/builds', [BuildController::class, 'userBuilds']);

    Route::get('/builds/{build}/ratings', [RatingController::class, 'index']);

    // Components Specific Filters
    Route::get('/cpus/socket/{socket_id}', [CpuController::class, 'bySocket']);
    Route::get('/motherboards/socket/{socket_id}', [MotherboardController::class, 'bySocket']);
    Route::get('/motherboards/ram_type/{ram_type_id}', [MotherboardController::class, 'byRamType']);
    Route::get('/rams/ram_type/{ram_type_id}', [RamController::class, 'byRamType']);

    // Components (Public Read-Only)
    Route::apiResource('cpus', CpuController::class)->only(['index', 'show']);
    Route::apiResource('gpus', GpuController::class)->only(['index', 'show']);
    Route::apiResource('rams', RamController::class)->only(['index', 'show']);
    Route::apiResource('motherboards', MotherboardController::class)->only(['index', 'show']);
    Route::apiResource('psus', PsuController::class)->only(['index', 'show']);
    Route::apiResource('drives', DriveController::class)->only(['index', 'show']);
    Route::apiResource('chassis', ChassisController::class)->only(['index', 'show']);

    // Protected Routes (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/articles', [ArticleController::class, 'store']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
        
        Route::post('/builds', [BuildController::class, 'store']);
        Route::post('/builds/{build}/ratings', [RatingController::class, 'store']);
    });
});
