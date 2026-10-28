<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(int $year, int $month, int $day, Article $article)
    {
        abort_unless($article->is_published && $article->published_at && $article->published_at->isPast(), 404);

        $publishedDate = $article->published_at->copy()->timezone(config('app.timezone'));
        abort_unless(
            $publishedDate->year === $year
            && $publishedDate->month === $month
            && $publishedDate->day === $day,
            404
        );

        $article->incrementViews();
        $article->loadMissing(['category', 'categories', 'approvedComments.user']);

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

        $previousArticle = Article::query()
            ->published()
            ->where('published_at', '<', $article->published_at)
            ->latest('published_at')
            ->first();

        $nextArticle = Article::query()
            ->published()
            ->where('published_at', '>', $article->published_at)
            ->oldest('published_at')
            ->first();

        $categories = Category::nav()->get();

        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'trendingArticles' => $trendingArticles,
            'latestArticles' => $latestArticles,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
            'categories' => $categories,
        ]);
    }

    /** Permanently redirect former slug URLs to the canonical date-and-ID URL. */
    public function redirectLegacy(Article $article)
    {
        abort_unless($article->is_published && $article->published_at && $article->published_at->isPast(), 404);

        return redirect()->to($article->publicUrl(), 301);
    }

    public function category(Category $category)
    {
        $articles = Article::query()
            ->where(function ($q) use ($category) {
                $q->where('category_id', $category->id)
                  ->orWhereHas('categories', fn ($catQ) => $catQ->where('categories.id', $category->id));
            })
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $trendingArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->trending()
            ->take(5)
            ->get();

        $latestArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = Category::nav()
            ->withCount(['articles as published_articles_count' => function ($query) {
                $query->published();
            }])
            ->get();

        return view('articles.index', [
            'category' => $category,
            'heading' => $category->name,
            'articles' => $articles,
            'trendingArticles' => $trendingArticles,
            'latestArticles' => $latestArticles,
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

        $latestArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = Category::nav()
            ->withCount(['articles as published_articles_count' => function ($query) {
                $query->published();
            }])
            ->get();

        return view('articles.index', [
            'heading' => $query === '' ? 'खोज्नुहोस्' : 'खोज नतिजा: '.$query,
            'query' => $query,
            'articles' => $articles,
            'trendingArticles' => $trendingArticles,
            'latestArticles' => $latestArticles,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created comment for an article.
     */
    public function storeComment(Request $request, Article $article)
    {
        abort_unless($article->is_published && $article->published_at && $article->published_at->isPast(), 404);

        if (! auth()->check()) {
            return redirect()
                ->to($article->publicUrl() . '#comments')
                ->with('status_warning', 'प्रतिक्रिया लेख्नका लागि कृपया पहिले लगइन गर्नुहोस्।');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ], [
            'content.required' => 'कृपया आफ्नो प्रतिक्रिया लेख्नुहोस्।',
            'content.min' => 'प्रतिक्रिया कम्तीमा २ अक्षरको हुनुपर्छ।',
            'content.max' => 'प्रतिक्रिया २,००० अक्षरभन्दा बढी हुन सक्दैन।',
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            $parentComment = \App\Models\Comment::where('id', $parentId)
                ->where('article_id', $article->id)
                ->first();
            if (! $parentComment) {
                $parentId = null;
            }
        }

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $parentId,
            'content' => strip_tags(trim($validated['content'])),
            'is_approved' => true,
        ]);

        $message = $parentId ? 'तपाईंको जवाफ सफलतापूर्वक पोस्ट गरियो।' : 'तपाईंको प्रतिक्रिया सफलतापूर्वक पोस्ट गरियो।';

        return redirect()
            ->to($article->publicUrl() . '#comments')
            ->with('comment_success', $message);
    }

    /**
     * Delete a comment (author or admin only).
     */
    public function destroyComment(Request $request, \App\Models\Comment $comment)
    {
        abort_unless(auth()->check(), 403);

        if ($comment->user_id !== $request->user()->id) {
            abort(403);
        }

        $article = $comment->article;
        $comment->delete();

        $redirectUrl = $article ? $article->publicUrl() . '#comments' : url()->previous();

        return redirect($redirectUrl)->with('comment_success', 'प्रतिक्रिया हटाइयो।');
    }
}
