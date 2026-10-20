<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\MenuItem;
use App\Services\YouTubeService;
use Livewire\Attributes\Url;
use Livewire\Component;

class NewsHomepage extends Component
{
    #[Url(as: 'q', except: '')]
    public string $query = '';

    public string $activeProvince = 'लुम्बिनी प्रदेश';

    public bool $flashOpen = false;

    public function search(): void
    {
        $this->validate(['query' => ['required', 'string', 'min:2']]);
        $this->redirectRoute('search', ['q' => $this->query], navigate: true);
    }

    public function selectProvince(string $province): void
    {
        $this->activeProvince = $province;
    }

    public function render()
    {
        // Do not cache hydrated articles in the database cache store: article bodies
        // can make the serialized value larger than MySQL's max_allowed_packet.
        // Query caching for these results needs Redis or a dedicated cache store.
        // 1. Featured Lead Story
        $leadStory = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->first() ?? Article::query()->with(['category', 'categories'])->published()->latest('published_at')->first();

        // 2. Breaking News Ticker (Breaking or latest)
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

        // 3. Flash News (Drawer)
        $flashNews = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        // 4. Top Headlines Grid (excluding lead if possible)
        $headlines = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->when($leadStory, fn ($q) => $q->where('id', '!=', $leadStory->id))
            ->latest('published_at')
            ->take(8)
            ->get();

        // 5. Trending News (ordered by views)
        $trendingNews = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->trending()
            ->take(5)
            ->get();

        // 6. Section Articles by Category
        $kapilvastuSpecial = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('kapilvastu-special')
            ->latest('published_at')
            ->take(6)
            ->get();

        $politicsArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('politics')
            ->latest('published_at')
            ->take(4)
            ->get();

        $economyArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('economy')
            ->latest('published_at')
            ->take(5)
            ->get();

        $sportsArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('sports')
            ->latest('published_at')
            ->take(5)
            ->get();

        $societyArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('society')
            ->latest('published_at')
            ->take(4)
            ->get();

        $securityArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('crime-security')
            ->latest('published_at')
            ->take(5)
            ->get();

        $opinionArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('opinion')
            ->latest('published_at')
            ->take(4)
            ->get();

        $healthArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('health')
            ->latest('published_at')
            ->take(4)
            ->get();

        $youtubeService = app(YouTubeService::class);
        $videoSectionData = $youtubeService->getVideosForHomepage(4);

        $videoArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('video')
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(4)
            ->get();

        // 7. Provinces Data
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
                ->with(['category', 'categories'])
                ->published()
                ->where('province', $prov)
                ->latest('published_at')
                ->take(4)
                ->get();

            if ($provArticles->count() < 4) {
                $additional = Article::query()
                    ->with(['category', 'categories'])
                    ->published()
                    ->whereNotIn('id', $provArticles->pluck('id'))
                    ->latest('published_at')
                    ->take(4 - $provArticles->count())
                    ->get();
                $provArticles = $provArticles->concat($additional);
            }

            $provinceStories[$prov] = $provArticles;
        }

        $headerMenuItems = MenuItem::query()
            ->active()
            ->header()
            ->root()
            ->with(['children' => fn ($q) => $q->active()->ordered()->with('category'), 'category'])
            ->ordered()
            ->get();

        $homepageData = [
            'leadStory' => $leadStory,
            'breakingNews' => $breakingNews,
            'flashNews' => $flashNews,
            'headlines' => $headlines,
            'trendingNews' => $trendingNews,
            'kapilvastuSpecial' => $kapilvastuSpecial,
            'politicsArticles' => $politicsArticles,
            'economyArticles' => $economyArticles,
            'sportsArticles' => $sportsArticles,
            'societyArticles' => $societyArticles,
            'securityArticles' => $securityArticles,
            'opinionArticles' => $opinionArticles,
            'healthArticles' => $healthArticles,
            'videoArticles' => $videoArticles,
            'videoSectionData' => $videoSectionData,
            'provincesList' => $provincesList,
            'provinceStories' => $provinceStories,
            'categories' => Category::nav()->get(),
            'headerMenuItems' => $headerMenuItems,
        ];

        return view('livewire.news-homepage', $homepageData);
    }
}
