<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\MenuItem;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once __DIR__.'/../helpers.php';

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
            ->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
        RateLimiter::for('registration', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('password-reset', fn (Request $request) => Limit::perMinute(3)
            ->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('notification-subscription', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
        RateLimiter::for('public-api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        // Disable Vite preload tags to prevent redundant link preload warnings on Livewire SPA navigation
        Vite::usePreloadTagAttributes(false);

        // Configure Carbon with Nepali digits for relative time diffs (e.g. १९ घण्टा अगाडि)
        Carbon::setLocale('ne');
        CarbonImmutable::setLocale('ne');

        $toNepaliTime = function ($time, string $suffix = ''): string {
            return nepali_number($time).$suffix;
        };

        Carbon::getTranslator()->setMessages('ne', [
            'alt_numbers' => ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'],
            'ago' => fn ($time) => $toNepaliTime($time, ' अगाडि'),
            'from_now' => fn ($time) => $toNepaliTime($time, 'मा'),
            'after' => fn ($time) => $toNepaliTime($time, ' पछि'),
            'before' => fn ($time) => $toNepaliTime($time, ' अघि'),
        ]);

        Blade::directive('nepali', function ($expression) {
            return "<?php echo nepali_number($expression); ?>";
        });

        View::composer(['livewire.news-homepage', 'articles.*', 'home', 'partials.*', 'components.layouts.*'], function ($view) {
            if (Schema::hasTable('categories')) {
                $categories = Cache::remember('portal.navigation.categories', now()->addMinutes(15), fn () => Category::nav()->get());
                $view->with('categories', $categories);
            }

            if (Schema::hasTable('menu_items')) {
                $headerMenuItems = Cache::remember('portal.navigation.menu', now()->addMinutes(15), fn () => MenuItem::query()
                    ->active()
                    ->header()
                    ->root()
                    ->with([
                        'children' => fn ($q) => $q->active()->ordered()->with(['category', 'page']),
                        'category',
                        'page',
                    ])
                    ->ordered()
                    ->get());

                $view->with('headerMenuItems', $headerMenuItems);
            }

            if (Schema::hasTable('articles')) {
                // Article bodies can exceed the MySQL database cache packet limit.
                // Keep this data uncached until a Redis/file cache store is configured.
                $flashNews = Article::query()
                    ->with(['category', 'categories'])
                    ->published()
                    ->latest('published_at')
                    ->take(6)
                    ->get();
                $breakingNews = Article::query()
                    ->published()
                    ->where(function ($q) {
                        $q->where('is_breaking', true)->orWhere('is_featured', true);
                    })
                    ->latest('published_at')
                    ->take(6)
                    ->get();
                if ($breakingNews->isEmpty()) {
                    $breakingNews = Article::query()->published()->latest('published_at')->take(5)->get();
                }
                $provincesList = [
                    'कोशी प्रदेश',
                    'मधेश प्रदेश',
                    'बागमती प्रदेश',
                    'गण्डकी प्रदेश',
                    'लुम्बिनी प्रदेश',
                    'कर्णाली प्रदेश',
                    'सुदूरपश्चिम प्रदेश',
                ];

                $provinceStories = [];
                foreach ($provincesList as $prov) {
                    $provArticles = Article::query()
                        ->with('category')
                        ->published()
                        ->where('province', $prov)
                        ->latest('published_at')
                        ->take(4)
                        ->get();

                    if ($provArticles->count() < 4) {
                        $additional = Article::query()
                            ->with('category')
                            ->published()
                            ->whereNotIn('id', $provArticles->pluck('id'))
                            ->latest('published_at')
                            ->take(4 - $provArticles->count())
                            ->get();
                        $provArticles = $provArticles->concat($additional);
                    }

                    $provinceStories[$prov] = $provArticles;
                }

                $headerNews = compact('flashNews', 'breakingNews', 'provincesList', 'provinceStories');
                $view->with($headerNews);
            }
        });
    }
}
