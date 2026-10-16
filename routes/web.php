<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RashifalController;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/search', [ArticleController::class, 'search'])
    ->middleware('throttle:60,1')
    ->name('search');

Route::get('/category/{category:slug}', [ArticleController::class, 'category'])
    ->name('category.show');

Route::get('/news/{article:slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

Route::get('/rashifal/{rashi?}', [RashifalController::class, 'show'])
    ->name('rashifal.show');

Route::get('/ashifal/{rashi?}', function ($rashi = 'karkat') {
    return redirect()->route('rashifal.show', $rashi ?? 'karkat');
});

Route::get('/ads/{advertisement}/click', [AdvertisementController::class, 'click'])
    ->name('ads.click');

Route::get('/sitemap.xml', function () {
    $articles = Article::published()->latest('published_at')->take(200)->get();
    $categories = Category::all();

    return response()
        ->view('sitemap', compact('articles', 'categories'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
