<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BuildController;
use App\Http\Controllers\Api\V1\ComponentController;
use App\Http\Controllers\Api\V1\RatingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/check-user', [AuthController::class, 'checkUser']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    Route::get('/components/{type}/filters', [ComponentController::class, 'filters']);
    Route::get('/components/{type}', [ComponentController::class, 'index']);

    Route::get('/builds/random', [BuildController::class, 'random']);
    Route::middleware('throttle:20,1')->get('/builds/{build}/ai-summary', [BuildController::class, 'aiSummary']);
    Route::get('/users/{user}/builds', [BuildController::class, 'userBuilds']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/articles', [ArticleController::class, 'store']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

        Route::post('/builds', [BuildController::class, 'store']);
        Route::put('/builds/{build}', [BuildController::class, 'update']);
        Route::delete('/builds/{build}', [BuildController::class, 'destroy']);
        Route::post('/builds/{build}/ratings', [RatingController::class, 'store']);
    });
});
