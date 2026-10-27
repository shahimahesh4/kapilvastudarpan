<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        return $this->xml('sitemap.index');
    }

    public function pages(): Response
    {
        return $this->xml('sitemap.pages', [
            'articles' => Article::published()->orderByDesc('published_at')->get(),
            'categories' => Category::nav()->get(),
            'pages' => Page::published()->ordered()->get(),
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
