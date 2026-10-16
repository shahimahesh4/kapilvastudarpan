<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
                        'children' => fn ($q) => $q->active()->ordered()->with('category'),
                        'category',
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
