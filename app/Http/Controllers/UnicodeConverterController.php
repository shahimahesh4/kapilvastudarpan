<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\NepaliUnicodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnicodeConverterController extends Controller
{
    public function __construct(
        protected NepaliUnicodeService $unicodeService
    ) {}

    /**
     * Display the Nepali Unicode Converter tool page.
     */
    public function index(Request $request)
    {
        $mode = $request->query('mode', 'romanized');
        if (! in_array($mode, ['romanized', 'preeti', 'unicode-to-preeti'])) {
            $mode = 'romanized';
        }

        $inputText = (string) $request->query('text', '');
        $convertedText = '';

        if ($inputText !== '') {
            $convertedText = match ($mode) {
                'preeti' => $this->unicodeService->preetiToUnicode($inputText),
                'unicode-to-preeti' => $this->unicodeService->unicodeToPreeti($inputText),
                default => $this->unicodeService->romanizedToUnicode($inputText),
            };
        }

        $latestArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        $trendingArticles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->trending()
            ->take(5)
            ->get();

        $categories = Category::nav()->get();

        return view('pages.unicode-converter', [
            'mode' => $mode,
            'inputText' => $inputText,
            'convertedText' => $convertedText,
            'latestArticles' => $latestArticles,
            'trendingArticles' => $trendingArticles,
            'categories' => $categories,
        ]);
    }

    /**
     * API endpoint for server-side conversion fallback or programmatic calls.
     */
    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'nullable|string|max:5000',
            'mode' => 'required|string|in:romanized,preeti,unicode-to-preeti',
        ]);

        $text = $validated['text'] ?? '';
        $mode = $validated['mode'];

        $result = match ($mode) {
            'preeti' => $this->unicodeService->preetiToUnicode($text),
            'unicode-to-preeti' => $this->unicodeService->unicodeToPreeti($text),
            default => $this->unicodeService->romanizedToUnicode($text),
        };

        $words = count(preg_split('/\s+/u', trim($result), -1, PREG_SPLIT_NO_EMPTY));
        $chars = mb_strlen($result);

        return response()->json([
            'success' => true,
            'result' => $result,
            'words' => $words,
            'chars' => $chars,
            'mode' => $mode,
        ]);
    }
}
