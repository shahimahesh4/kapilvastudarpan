<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
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
        require_once __DIR__ . '/../helpers.php';

        // Disable Vite preload tags to prevent redundant link preload warnings on Livewire SPA navigation
        Vite::usePreloadTagAttributes(false);

        // Configure Carbon with Nepali digits for relative time diffs (e.g. १९ घण्टा अगाडि)
        \Carbon\Carbon::setLocale('ne');
        \Carbon\CarbonImmutable::setLocale('ne');

        $toNepaliTime = function ($time, string $suffix = ''): string {
            return nepali_number($time) . $suffix;
        };

        \Carbon\Carbon::getTranslator()->setMessages('ne', [
            'alt_numbers' => ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'],
            'ago' => fn ($time) => $toNepaliTime($time, ' अगाडि'),
            'from_now' => fn ($time) => $toNepaliTime($time, 'मा'),
            'after' => fn ($time) => $toNepaliTime($time, ' पछि'),
            'before' => fn ($time) => $toNepaliTime($time, ' अघि'),
        ]);

        \Illuminate\Support\Facades\Blade::directive('nepali', function ($expression) {
            return "<?php echo nepali_number($expression); ?>";
        });

        View::composer(['livewire.news-homepage', 'articles.*', 'home', 'partials.*', 'components.layouts.*'], function ($view) {
            if (Schema::hasTable('categories')) {
                $categories = \App\Models\Category::nav()->get();
                $view->with('categories', $categories);
            }

            if (Schema::hasTable('menu_items')) {
                $headerMenuItems = MenuItem::query()
                    ->active()
                    ->header()
                    ->root()
                    ->with([
                        'children' => fn ($q) => $q->active()->ordered()->with(['category', 'page']),
                        'category',
                        'page',
                    ])
                    ->ordered()
                    ->get();

                $view->with('headerMenuItems', $headerMenuItems);
            }

            if (Schema::hasTable('articles')) {
                $flashNews = \App\Models\Article::query()
                    ->with(['category', 'categories'])
                    ->published()
                    ->latest('published_at')
                    ->take(6)
                    ->get();
                $view->with('flashNews', $flashNews);

                $breakingNews = \App\Models\Article::query()
                    ->published()
                    ->where(function ($q) {
                        $q->where('is_breaking', true)->orWhere('is_featured', true);
                    })
                    ->latest('published_at')
                    ->take(6)
                    ->get();
                if ($breakingNews->isEmpty()) {
                    $breakingNews = \App\Models\Article::query()->published()->latest('published_at')->take(5)->get();
                }
                $view->with('breakingNews', $breakingNews);

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
                    $provArticles = \App\Models\Article::query()
                        ->with('category')
                        ->published()
                        ->where('province', $prov)
                        ->latest('published_at')
                        ->take(4)
                        ->get();

                    if ($provArticles->count() < 4) {
                        $additional = \App\Models\Article::query()
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

                $view->with('provincesList', $provincesList);
                $view->with('provinceStories', $provinceStories);
            }
        });
    }
}
