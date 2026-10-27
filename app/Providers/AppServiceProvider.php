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

        });

        // Header content is needed only by the header partial. Cache compact card
        // records in the file store so MySQL never serializes article bodies.
        View::composer('partials.header', function ($view) {
            if (! Schema::hasTable('articles')) {
                return;
            }

            $headerNews = Cache::store('file')->remember('portal.header-news.v2', now()->addMinutes(2), function (): array {
                $flashNews = Article::query()->forCard()->published()->latest('published_at')->take(6)->get();
                $breakingNews = Article::query()->forCard()->published()
                    ->where(fn ($query) => $query->where('is_breaking', true)->orWhere('is_featured', true))
                    ->latest('published_at')->take(6)->get();
                if ($breakingNews->isEmpty()) {
                    $breakingNews = Article::query()->forCard()->published()->latest('published_at')->take(5)->get();
                }

                $provincesList = ['कोशी प्रदेश', 'मधेश प्रदेश', 'बागमती प्रदेश', 'गण्डकी प्रदेश', 'लुम्बिनी प्रदेश', 'कर्णाली प्रदेश', 'सुदूरपश्चिम प्रदेश'];
                $provinceStories = [];
                foreach ($provincesList as $province) {
                    $provinceStories[$province] = Article::query()->forCard()->published()
                        ->where('province', $province)->latest('published_at')->take(4)->get();
                }

                return compact('flashNews', 'breakingNews', 'provincesList', 'provinceStories');
            });

            $view->with($headerNews);
        });
    }
}
