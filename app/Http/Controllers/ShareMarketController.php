<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\NepseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareMarketController extends Controller
{
    public function __construct(
        protected NepseService $nepseService
    ) {}

    /**
     * Check if share market feature is enabled globally.
     */
    protected function ensureEnabled(): void
    {
        if (!setting_bool('sharemarket_enabled', true)) {
            abort(404, 'नेपाल शेयर बजार फिचर हाललाई निष्क्रिय गरिएको छ।');
        }
    }

    /**
     * Display the main Share Market (NEPSE) overview page.
     */
    public function index(): View
    {
        $this->ensureEnabled();

        $marketData = $this->nepseService->getMarketData();

        // Fetch related business / economic articles
        $businessNews = Article::published()
            ->whereHas('categories', fn ($q) => $q->where('slug', 'arthatantra')->orWhere('slug', 'अर्थतन्त्र'))
            ->latest('published_at')
            ->take(6)
            ->get();

        if ($businessNews->isEmpty()) {
            $businessNews = Article::published()
                ->latest('published_at')
                ->take(6)
                ->get();
        }

        return view('pages.sharemarket', [
            'marketData' => $marketData,
            'businessNews' => $businessNews,
            'title' => 'नेपाल शेयर बजार (NEPSE Live Market) | ' . setting('site_name', 'कपिलवस्तु दर्पण'),
            'metaDescription' => 'नेपाल स्टक एक्सचेन्ज (NEPSE) को ताजा लाइभ बजार परिसूचक, टप गेनर्स, टप लुजर्स, टर्नओभर तथा विभिन्न उप-परिसूचकहरू (Sectors) को प्रत्यक्ष विवरण।',
        ]);
    }

    /**
     * Live JSON endpoint for frontend auto-refresh or ticker polling.
     */
    public function live(): JsonResponse
    {
        if (!setting_bool('sharemarket_enabled', true)) {
            return response()->json([
                'status' => 'disabled',
                'message' => 'Share market feature is currently disabled.',
                'data' => null,
                'ticker' => [],
            ], 404);
        }

        $marketData = $this->nepseService->getMarketData();

        return response()->json([
            'status' => 'success',
            'data' => $marketData,
            'ticker' => $this->nepseService->getTickerItems(),
            'timestamp' => now('Asia/Kathmandu')->toIso8601String(),
        ]);
    }

    /**
     * Ticker items only (lightweight payload).
     */
    public function ticker(): JsonResponse
    {
        if (!setting_bool('sharemarket_enabled', true)) {
            return response()->json([
                'status' => 'disabled',
                'message' => 'Share market feature is currently disabled.',
                'ticker' => [],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'ticker' => $this->nepseService->getTickerItems(),
        ]);
    }

    /**
     * Display the stock ticker detail page (similar to OnlineKhabar /markets/ticker/{symbol}).
     */
    public function showTicker(string $symbol): View
    {
        $this->ensureEnabled();

        $symbol = strtoupper(trim($symbol));
        $company = $this->nepseService->getCompanyDetails($symbol);
        $marketData = $this->nepseService->getMarketData();

        // Fetch related business / economy articles
        $businessNews = Article::published()
            ->whereHas('categories', fn ($q) => $q->where('slug', 'arthatantra')->orWhere('slug', 'अर्थतन्त्र'))
            ->latest('published_at')
            ->take(4)
            ->get();

        if ($businessNews->isEmpty()) {
            $businessNews = Article::published()->latest('published_at')->take(4)->get();
        }

        $title = "{$company['name']} ({$symbol}) | शेयर मूल्य, चार्ट, वित्तीय विवरण - " . setting('site_name', 'कपिलवस्तु दर्पण');
        $metaDescription = "{$company['name']} ({$symbol}) को ताजा नेप्से शेयर मूल्य रु. {$company['ltp']}, प्राविधिक सूचक (Technical Indicators), सक्रिय ब्रोकर विवरण, त्रैमासिक वित्तीय रिपोर्ट तथा सूचनाहरू।";

        return view('pages.sharemarket-ticker', [
            'company' => $company,
            'symbol' => $symbol,
            'marketData' => $marketData,
            'businessNews' => $businessNews,
            'title' => $title,
            'metaDescription' => $metaDescription,
        ]);
    }

    /**
     * JSON API endpoint for individual ticker details.
     */
    public function tickerDetailApi(string $symbol): JsonResponse
    {
        if (!setting_bool('sharemarket_enabled', true)) {
            return response()->json([
                'status' => 'disabled',
                'message' => 'Share market feature is currently disabled.',
                'data' => null,
            ], 404);
        }

        $symbol = strtoupper(trim($symbol));
        $company = $this->nepseService->getCompanyDetails($symbol);

        return response()->json([
            'status' => 'success',
            'symbol' => $symbol,
            'data' => $company,
            'timestamp' => now('Asia/Kathmandu')->toIso8601String(),
        ]);
    }
}
