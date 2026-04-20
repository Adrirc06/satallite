<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'home']);
Route::get('/articles', [IndexController::class, 'articles']);
Route::get('/articles/create', [IndexController::class, 'createArticle']);
Route::get('/builder', [IndexController::class, 'builder']);
Route::get('/login', [IndexController::class, 'login'])->name('login');
Route::get('/signup', [IndexController::class, 'signup']);
Route::get('/article/{id}', [IndexController::class, 'article'])->name('article.show');
Route::get('/profile', [IndexController::class, 'profile'])->middleware('auth');
Route::get('/profile/edit', [IndexController::class, 'editProfile'])->middleware('auth');

Route::post('/signup', [RegisterController::class, 'store']);

Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth');
Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.updateImage')->middleware('auth');
Route::delete('/profile/image', [ProfileController::class, 'destroyImage'])->name('profile.destroyImage')->middleware('auth');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->middleware('auth');
Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('auth');
