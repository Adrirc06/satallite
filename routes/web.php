<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'home']);
Route::get('/articles', [IndexController::class, 'articles']);
Route::get('/builder', [IndexController::class, 'builder']);
Route::get('/login', [IndexController::class, 'login']);
Route::get('/signup', [IndexController::class, 'signup']);
Route::get('/profile', [IndexController::class, 'profile']);
Route::get('/article/{id}', [IndexController::class, 'article'])->name('article.show');