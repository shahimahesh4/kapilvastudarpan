<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        abort_unless($article->published_at && $article->published_at->isPast(), 404);

        return view('articles.show', compact('article'));
    }

    public function category(Category $category)
    {
        return view('articles.index', [
            'heading' => $category->name,
            'articles' => $category->articles()->published()->latest('published_at')->paginate(12),
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->string('q'));

        return view('articles.index', [
            'heading' => $query === '' ? 'खोज्नुहोस्' : 'खोज नतिजा: '.$query,
            'articles' => Article::query()->with('category')->published()
                ->when($query !== '', fn ($articles) => $articles->where(fn ($builder) => $builder->where('title', 'like', "%{$query}%")->orWhere('excerpt', 'like', "%{$query}%")))
                ->latest('published_at')->paginate(12)->withQueryString(),
        ]);
    }
}
