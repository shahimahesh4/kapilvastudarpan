<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    private const URLS_PER_SITEMAP = 45000;

    public function index(): Response
    {
        $articleSitemapCount = max(1, (int) ceil(Article::published()->count() / self::URLS_PER_SITEMAP));

        return $this->xml('sitemap.index', compact('articleSitemapCount'));
    }

    public function pages(int $page = 1): Response
    {
        abort_unless($page >= 1, 404);

        return $this->xml('sitemap.pages', [
            'articles' => Article::published()
                ->select(['id', 'slug', 'updated_at', 'published_at'])
                ->orderByDesc('published_at')
                ->forPage($page, self::URLS_PER_SITEMAP)
                ->get(),
            'categories' => $page === 1 ? Category::nav()->get() : collect(),
            'pages' => $page === 1 ? Page::published()->ordered()->get() : collect(),
        ]);
    }

    public function news(): Response
    {
        return $this->xml('sitemap.news', [
            'articles' => Article::published()
                ->where('published_at', '>=', now()->subDays(2))
                ->orderByDesc('published_at')
                ->take(1000)
                ->get(),
        ]);
    }

    /** @param array<string, mixed> $data */
    private function xml(string $view, array $data = []): Response
    {
        return response()
            ->view($view, $data)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
