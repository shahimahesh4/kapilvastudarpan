<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        abort_unless($article->is_published && $article->published_at && $article->published_at->isPast(), 404);

        $article->incrementViews();
        $article->loadMissing(['category', 'categories']);

        $categoryIds = $article->categories->pluck('id')->all();
        if (empty($categoryIds) && $article->category_id) {
            $categoryIds = [$article->category_id];
        }

        $relatedArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($categoryIds, $article) {
                if (! empty($categoryIds)) {
                    $q->whereHas('categories', fn ($catQ) => $catQ->whereIn('categories.id', $categoryIds))
                      ->orWhereIn('category_id', $categoryIds);
                } else {
                    $q->where('category_id', $article->category_id);
                }
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        $trendingArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->where('id', '!=', $article->id)
            ->trending()
            ->take(5)
            ->get();

        $latestArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(6)
            ->get();

        $categories = Category::nav()->get();

        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'trendingArticles' => $trendingArticles,
            'latestArticles' => $latestArticles,
            'categories' => $categories,
        ]);
    }

    public function category(Category $category)
    {
        $articles = $category->articles()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $trendingArticles = Article::query()
            ->with('category')
            ->published()
            ->trending()
            ->take(5)
            ->get();

        $categories = Category::nav()->get();

        return view('articles.index', [
            'category' => $category,
            'heading' => $category->name,
            'articles' => $articles,
            'trendingArticles' => $trendingArticles,
            'categories' => $categories,
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->string('q'));

        $articles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%");
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $trendingArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->trending()
            ->take(5)
            ->get();

        $categories = Category::nav()->get();

        return view('articles.index', [
            'heading' => $query === '' ? 'खोज्नुहोस्' : 'खोज नतिजा: '.$query,
            'query' => $query,
            'articles' => $articles,
            'trendingArticles' => $trendingArticles,
            'categories' => $categories,
        ]);
    }
}
