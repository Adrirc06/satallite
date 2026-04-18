<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'home']);
Route::get('/articles', [IndexController::class, 'articles']);
Route::get('/builder', [IndexController::class, 'builder']);
Route::get('/login', [IndexController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/signup', [IndexController::class, 'signup']);
Route::post('/signup', [RegisterController::class, 'store']);
Route::get('/profile', [IndexController::class, 'profile'])->middleware('auth');
Route::get('/article/{id}', [IndexController::class, 'article'])->name('article.show');
