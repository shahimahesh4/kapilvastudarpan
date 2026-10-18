<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RashifalController;
use App\Http\Controllers\ShareMarketController;
use App\Http\Controllers\UnicodeConverterController;
use App\Http\Controllers\DatabaseBackupController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::redirect('/admin', '/stnapanel');

// Admin Database Backup Export / Download (.sql)
Route::get('/stnapanel/database/export', [DatabaseBackupController::class, 'export'])
    ->name('admin.database.export')
    ->middleware(['web', 'auth']);

// Frontend User Authentication & Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Frontend User Dashboard
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::put('/password', [DashboardController::class, 'updatePassword'])->name('dashboard.password.update');
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::get('/search', [ArticleController::class, 'search'])
    ->middleware('throttle:60,1')
    ->name('search');

Route::get('/category/{category:slug}', [ArticleController::class, 'category'])
    ->name('category.show');

Route::get('/news/{article:slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

Route::post('/news/{article:slug}/comments', [ArticleController::class, 'storeComment'])
    ->name('articles.comments.store');

Route::delete('/comments/{comment}', [ArticleController::class, 'destroyComment'])
    ->name('comments.destroy');

Route::post('/contact/submit', [PageController::class, 'submitContact'])
    ->name('contact.submit');

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
    $pages = Page::published()->ordered()->get();

    return response()
        ->view('sitemap', compact('articles', 'categories', 'pages'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Nepali Unicode Converter (Romanized, Preeti to Unicode, Unicode to Preeti)
Route::get('/unicode-converter', [UnicodeConverterController::class, 'index'])->name('unicode.converter');
Route::get('/unicode', function () {
    return redirect()->route('unicode.converter');
});
Route::post('/api/unicode/convert', [UnicodeConverterController::class, 'convert'])->name('api.unicode.convert');

// Nepal Share Market (NEPSE Live Data)
Route::get('/sharemarket', [ShareMarketController::class, 'index'])->name('sharemarket.index');
Route::get('/sharemarket/ticker/{symbol}', [ShareMarketController::class, 'showTicker'])->name('sharemarket.ticker');
Route::get('/markets/ticker/{symbol}', [ShareMarketController::class, 'showTicker'])->name('markets.ticker');
Route::get('/nepse', function () {
    if (!setting_bool('sharemarket_enabled', true)) {
        abort(404);
    }
    return redirect()->route('sharemarket.index');
});
Route::get('/api/nepse/live', [ShareMarketController::class, 'live'])->name('api.nepse.live');
Route::get('/api/nepse/ticker', [ShareMarketController::class, 'ticker'])->name('api.nepse.ticker');
Route::get('/api/nepse/ticker/{symbol}', [ShareMarketController::class, 'tickerDetailApi'])->name('api.nepse.ticker.show');

// Web Push Notification Subscription Endpoints
Route::post('/api/notifications/subscribe', [NotificationSubscriptionController::class, 'subscribe'])->name('api.notifications.subscribe');
Route::post('/api/notifications/unsubscribe', [NotificationSubscriptionController::class, 'unsubscribe'])->name('api.notifications.unsubscribe');
Route::get('/api/notifications/test-payload', [NotificationSubscriptionController::class, 'testPayload'])->name('api.notifications.test-payload');

// Legacy 301 redirect from /page/{slug} to /{slug}
Route::get('/page/{slug}', function (string $slug) {
    return redirect()->route('pages.show', $slug, 301);
});

// Clean root-level page URLs (e.g., /our-team, /about-us, /contact)
Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('pages.show');
