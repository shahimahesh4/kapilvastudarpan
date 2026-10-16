<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::view('/', 'home')->name('home');
Route::get('/search', [ArticleController::class, 'search'])->name('search');
Route::get('/category/{category:slug}', [ArticleController::class, 'category'])->name('category.show');
Route::get('/news/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
