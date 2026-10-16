<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Component;

class NewsHomepage extends Component
{
    #[Url(as: 'q', except: '')]
    public string $query = '';

    #[Url(except: 'कोशी प्रदेश')]
    public string $province = 'कोशी प्रदेश';

    public bool $menuOpen = false;

    public bool $flashOpen = false;

    public function search(): void
    {
        $this->validate(['query' => ['required', 'string', 'min:2']]);
        $this->redirectRoute('search', ['q' => $this->query], navigate: true);
    }

    public function render()
    {
        $articles = Article::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->take(18)
            ->get()
            ->map(fn (Article $article) => [
                'title' => $article->title,
                'category' => $article->category->name,
                'time' => $article->published_at->format('Y-m-d H:i'),
                'url' => route('articles.show', $article),
                'featured' => $article->is_featured,
            ])
            ->all();

        return view('livewire.news-homepage', [
            'headlines' => $articles,
            'categories' => Category::query()->orderBy('name')->get(),
            'provinceArticles' => Article::query()->with('category')->published()
                ->where('province', $this->province)->latest('published_at')->take(5)->get(),
        ]);
    }
}
