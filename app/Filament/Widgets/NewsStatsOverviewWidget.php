<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -5;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('is_published', true)->count();
        $draftArticles = Article::where('is_published', false)->count();

        $totalViews = Article::sum('views') ?? 0;
        $totalCategories = Category::count();
        $activeAds = Advertisement::where('is_active', true)->count();
        $totalAds = Advertisement::count();
        $totalUsers = User::count();

        return [
            Stat::make('कुल समाचार (Articles)', nepali_number(number_format($totalArticles)))
                ->description(nepali_number($publishedArticles) . ' प्रकाशित • ' . nepali_number($draftArticles) . ' मस्यौदा')
                ->descriptionIcon('heroicon-m-newspaper')
                ->chart([8, 12, 16, 22, 28, 35, max($totalArticles, 10)])
                ->color('success')
                ->url('/stnapanel/articles'),

            Stat::make('कुल पाठक भ्युज (Total Views)', nepali_number(number_format($totalViews)))
                ->description('डिजिटल पाठक अन्तरक्रिया')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([30, 60, 95, 140, 210, 320, max($totalViews, 10)])
                ->color('info')
                ->url('/stnapanel/articles'),

            Stat::make('समाचार विधाहरू (Categories)', nepali_number($totalCategories))
                ->description('सक्रिय क्याटागोरी / विटहरू')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->chart([2, 4, 6, 8, 10, max($totalCategories, 5)])
                ->color('warning')
                ->url('/stnapanel/categories'),

            Stat::make('सक्रिय विज्ञापन (Advertisements)', nepali_number($activeAds))
                ->description(nepali_number($totalAds) . ' कुल विज्ञापन मध्ये सक्रिय')
                ->descriptionIcon('heroicon-m-megaphone')
                ->chart([1, 2, 4, 3, 5, max($activeAds, 2)])
                ->color('primary')
                ->url('/stnapanel/advertisements'),
        ];
    }
}
