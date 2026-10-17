<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified static/CMS page.
     */
    public function show(Page $page)
    {
        abort_unless($page->is_published && ($page->published_at === null || $page->published_at->isPast()), 404);

        $page->incrementViews();

        $latestArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(5)
            ->get();

        $trendingArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->trending()
            ->take(5)
            ->get();

        $categories = Category::nav()->get();

        $allPages = Page::query()
            ->published()
            ->ordered()
            ->get();

        $otherPages = $allPages->where('id', '!=', $page->id);

        return view('pages.show', [
            'page' => $page,
            'allPages' => $allPages,
            'latestArticles' => $latestArticles,
            'trendingArticles' => $trendingArticles,
            'categories' => $categories,
            'otherPages' => $otherPages,
        ]);
    }

    /**
     * Handle contact form submission from the contact page.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        // In a production app you could mail or store to database.
        return back()->with('success', 'धन्यवाद! तपाईंको सन्देश सफलतापूर्वक पठाइयो। हाम्रो टिमले चाँडै सम्पर्क गर्नेछ।');
    }
}
