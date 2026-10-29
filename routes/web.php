<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\OptimizedImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RashifalController;
use App\Http\Controllers\ShareMarketController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UnicodeConverterController;
use App\Http\Controllers\DatabaseBackupController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/optimized-image/{path}', [OptimizedImageController::class, 'show'])
    ->where('path', '.*')
    ->withoutMiddleware([
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ])
    ->name('images.optimized');
Route::redirect('/admin', '/stnapanel');

// Admin Database Backup Export / Download (.sql)
Route::get('/stnapanel/database/export', [DatabaseBackupController::class, 'export'])
    ->name('admin.database.export')
    ->middleware(['web', 'auth']);

// Frontend User Authentication & Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:registration');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware('throttle:password-reset')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-reset')->name('password.update');
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
    ->middleware('throttle:password-reset')
    ->name('verification.send');

Route::get('/search', [ArticleController::class, 'search'])
    ->middleware('throttle:60,1')
    ->name('search');

Route::get('/category/{category:slug}', [ArticleController::class, 'category'])
    ->name('category.show');

// Canonical article permalinks: /2026/10/28/193125
Route::get('/{year}/{month}/{day}/{article}', [ArticleController::class, 'show'])
    ->whereNumber(['year', 'month', 'day', 'article'])
    ->name('articles.show');

// Preserve existing shared article links and let search engines transfer their ranking signals.
Route::get('/news/{article:slug}', [ArticleController::class, 'redirectLegacy'])
    ->name('articles.legacy');

Route::post('/news/{article:slug}/comments', [ArticleController::class, 'storeComment'])
    ->name('articles.comments.store');

Route::delete('/comments/{comment}', [ArticleController::class, 'destroyComment'])
    ->name('comments.destroy');

Route::post('/contact/submit', [PageController::class, 'submitContact'])
    ->middleware('throttle:contact')
    ->name('contact.submit');

Route::get('/rashifal/{rashi?}', [RashifalController::class, 'show'])
    ->name('rashifal.show');

Route::get('/ashifal/{rashi?}', function ($rashi = 'karkat') {
    return redirect()->route('rashifal.show', $rashi ?? 'karkat');
});

Route::get('/ads/{advertisement}/click', [AdvertisementController::class, 'click'])
    ->name('ads.click');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-pages/{page}.xml', [SitemapController::class, 'pages'])->whereNumber('page')->name('sitemap.pages.page');
Route::get('/sitemap-news.xml', [SitemapController::class, 'news'])->name('sitemap.news');

// Nepali Unicode Converter (Romanized, Preeti to Unicode, Unicode to Preeti)
Route::get('/unicode-converter', [UnicodeConverterController::class, 'index'])->name('unicode.converter');
Route::get('/unicode', function () {
    return redirect()->route('unicode.converter');
});
Route::post('/api/unicode/convert', [UnicodeConverterController::class, 'convert'])->middleware('throttle:public-api')->name('api.unicode.convert');

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
Route::get('/api/nepse/live', [ShareMarketController::class, 'live'])->middleware('throttle:public-api')->name('api.nepse.live');
Route::get('/api/nepse/ticker', [ShareMarketController::class, 'ticker'])->middleware('throttle:public-api')->name('api.nepse.ticker');
Route::get('/api/nepse/ticker/{symbol}', [ShareMarketController::class, 'tickerDetailApi'])->middleware('throttle:public-api')->name('api.nepse.ticker.show');

// Web Push Notification Subscription Endpoints
Route::post('/api/notifications/subscribe', [NotificationSubscriptionController::class, 'subscribe'])->middleware('throttle:notification-subscription')->name('api.notifications.subscribe');
Route::post('/api/notifications/unsubscribe', [NotificationSubscriptionController::class, 'unsubscribe'])->middleware('throttle:notification-subscription')->name('api.notifications.unsubscribe');
Route::get('/api/notifications/test-payload', [NotificationSubscriptionController::class, 'testPayload'])->middleware('throttle:public-api')->name('api.notifications.test-payload');

// Legacy 301 redirect from /page/{slug} to /{slug}
Route::get('/page/{slug}', function (string $slug) {
    return redirect()->route('pages.show', $slug, 301);
});

// Clean root-level page URLs (e.g., /our-team, /about-us, /contact)
Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('pages.show');
