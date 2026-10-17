<?php

namespace App\Services;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    /**
     * Determine whether the video section should be displayed on the homepage.
     */
    public function isVideoSectionEnabled(): bool
    {
        return setting_bool('show_video_section', true);
    }

    /**
     * Determine whether YouTube API integration is enabled and configured.
     */
    public function isYouTubeApiEnabled(): bool
    {
        $enabled = setting_bool('youtube_api_enabled', false);
        $apiKey = trim(setting('youtube_api_key', ''));

        return $enabled && ! empty($apiKey);
    }

    /**
     * Parse a YouTube video ID from various URL formats or raw ID.
     */
    public static function parseVideoId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Standard 11 character ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Full or shortened URL patterns
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Fetch latest videos from YouTube Data API v3 with caching and error safety.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchYouTubeVideos(int $limit = 4): array
    {
        $apiKey = trim(setting('youtube_api_key', ''));
        if (empty($apiKey)) {
            return [];
        }

        $channelId = trim(setting('youtube_channel_id', ''));
        $playlistId = trim(setting('youtube_playlist_id', ''));
        $featuredUrl = trim(setting('youtube_featured_video_url', ''));
        $cacheMinutes = max(1, (int) setting('youtube_cache_minutes', 60));

        $cacheKey = 'youtube_videos_' . md5("{$apiKey}_{$channelId}_{$playlistId}_{$featuredUrl}_{$limit}");

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use ($apiKey, $channelId, $playlistId, $featuredUrl, $limit) {
            try {
                $videos = [];
                $featuredId = static::parseVideoId($featuredUrl);

                // If specific featured video requested, fetch its metadata first
                if ($featuredId) {
                    $featuredVideo = $this->fetchSingleVideoDetails($featuredId, $apiKey);
                    if ($featuredVideo) {
                        $videos[] = $featuredVideo;
                    }
                }

                // Remaining slots to fill from channel or playlist
                $remainingLimit = max(0, $limit - count($videos));

                if ($remainingLimit > 0) {
                    $fetchedVideos = [];

                    if (! empty($playlistId)) {
                        // 1. Fetch from Playlist
                        $fetchedVideos = $this->fetchFromPlaylist($playlistId, $apiKey, $remainingLimit);
                    } elseif (! empty($channelId)) {
                        // 2. Fetch from Channel
                        $fetchedVideos = $this->fetchFromChannel($channelId, $apiKey, $remainingLimit);
                    } elseif (empty($featuredId)) {
                        // 3. Fallback search query if no channel/playlist specified
                        $fetchedVideos = $this->fetchFromSearch('कपिलवस्तु दर्पण', $apiKey, $remainingLimit);
                    }

                    foreach ($fetchedVideos as $v) {
                        // Avoid duplicates if featured video is in the list
                        if ($featuredId && ($v['video_id'] ?? null) === $featuredId) {
                            continue;
                        }
                        $videos[] = $v;
                        if (count($videos) >= $limit) {
                            break;
                        }
                    }
                }

                return $videos;
            } catch (\Throwable $e) {
                Log::warning('YouTube API error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Fetch a single video's details by ID.
     */
    protected function fetchSingleVideoDetails(string $videoId, string $apiKey): ?array
    {
        try {
            $response = Http::timeout(5)->get('https://www.googleapis.com/youtube/v3/videos', [
                'part' => 'snippet,contentDetails',
                'id' => $videoId,
                'key' => $apiKey,
            ]);

            if ($response->successful() && ! empty($response->json('items'))) {
                $item = $response->json('items')[0];
                return $this->formatYouTubeItem($item, $videoId);
            }
        } catch (\Throwable $e) {
            Log::warning("YouTube single video fetch failed ({$videoId}): " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch videos from a playlist.
     */
    protected function fetchFromPlaylist(string $playlistId, string $apiKey, int $limit): array
    {
        try {
            $response = Http::timeout(5)->get('https://www.googleapis.com/youtube/v3/playlistItems', [
                'part' => 'snippet',
                'playlistId' => $playlistId,
                'maxResults' => $limit,
                'key' => $apiKey,
            ]);

            if ($response->successful()) {
                $items = $response->json('items') ?? [];
                $result = [];

                foreach ($items as $item) {
                    $vid = $item['snippet']['resourceId']['videoId'] ?? null;
                    if ($vid) {
                        $result[] = $this->formatYouTubeItem($item, $vid);
                    }
                }

                return $result;
            }
        } catch (\Throwable $e) {
            Log::warning("YouTube playlist fetch failed ({$playlistId}): " . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch videos from a channel.
     */
    protected function fetchFromChannel(string $channelId, string $apiKey, int $limit): array
    {
        try {
            $params = [
                'part' => 'snippet',
                'type' => 'video',
                'order' => 'date',
                'maxResults' => $limit,
                'key' => $apiKey,
            ];

            if (str_starts_with($channelId, 'UC')) {
                $params['channelId'] = $channelId;
            } else {
                // If it's a handle or name, search with query
                $params['q'] = $channelId;
            }

            $response = Http::timeout(5)->get('https://www.googleapis.com/youtube/v3/search', $params);

            if ($response->successful()) {
                $items = $response->json('items') ?? [];
                $result = [];

                foreach ($items as $item) {
                    $vid = $item['id']['videoId'] ?? null;
                    if ($vid) {
                        $result[] = $this->formatYouTubeItem($item, $vid);
                    }
                }

                return $result;
            }
        } catch (\Throwable $e) {
            Log::warning("YouTube channel search failed ({$channelId}): " . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch videos from a general search query.
     */
    protected function fetchFromSearch(string $query, string $apiKey, int $limit): array
    {
        try {
            $response = Http::timeout(5)->get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'q' => $query,
                'type' => 'video',
                'order' => 'date',
                'maxResults' => $limit,
                'key' => $apiKey,
            ]);

            if ($response->successful()) {
                $items = $response->json('items') ?? [];
                $result = [];

                foreach ($items as $item) {
                    $vid = $item['id']['videoId'] ?? null;
                    if ($vid) {
                        $result[] = $this->formatYouTubeItem($item, $vid);
                    }
                }

                return $result;
            }
        } catch (\Throwable $e) {
            Log::warning("YouTube search failed ({$query}): " . $e->getMessage());
        }

        return [];
    }

    /**
     * Format a YouTube API response item into a standardized array.
     */
    protected function formatYouTubeItem(array $item, string $videoId): array
    {
        $snippet = $item['snippet'] ?? [];
        $title = html_entity_decode($snippet['title'] ?? 'युट्युब भिडियो', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $description = $snippet['description'] ?? '';

        $thumbnails = $snippet['thumbnails'] ?? [];
        $thumbnail = $thumbnails['maxres']['url']
            ?? $thumbnails['high']['url']
            ?? $thumbnails['medium']['url']
            ?? $thumbnails['default']['url']
            ?? "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";

        $publishedAt = ! empty($snippet['publishedAt'])
            ? Carbon::parse($snippet['publishedAt'])
            : now();

        return [
            'id' => $videoId,
            'video_id' => $videoId,
            'title' => $title,
            'slug' => null,
            'excerpt' => $description,
            'image_url' => $thumbnail,
            'published_at' => $publishedAt,
            'url' => "https://www.youtube.com/watch?v={$videoId}",
            'embed_url' => "https://www.youtube.com/embed/{$videoId}",
            'duration' => 'HD भिडियो',
            'is_youtube' => true,
            'category_name' => 'भिडियो',
        ];
    }

    /**
     * Get default video articles from the database as fallback.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getDefaultVideos(int $limit = 4): array
    {
        $articles = Article::query()
            ->with(['category', 'categories'])
            ->published()
            ->forCategory('video')
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take($limit)
            ->get();

        $formatted = [];

        foreach ($articles as $article) {
            $cat = $article->categories->first() ?? $article->category;
            $videoId = static::parseVideoId($article->source_url);

            $formatted[] = [
                'id' => $article->id,
                'video_id' => $videoId,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'image_url' => $article->image_url,
                'published_at' => $article->published_at ?? $article->created_at,
                'url' => route('articles.show', $article->slug),
                'embed_url' => $videoId ? "https://www.youtube.com/embed/{$videoId}" : null,
                'duration' => 'HD ०५:२४',
                'is_youtube' => false,
                'category_name' => $cat?->name ?? 'भिडियो',
            ];
        }

        return $formatted;
    }

    /**
     * Get the complete video package for the homepage.
     *
     * @return array<string, mixed>
     */
    public function getVideosForHomepage(int $limit = 4): array
    {
        $enabled = $this->isVideoSectionEnabled();
        $title = setting('video_section_title', 'भिडियो विशेष');

        if (! $enabled) {
            return [
                'enabled' => false,
                'is_youtube' => false,
                'title' => $title,
                'lead' => null,
                'secondary' => [],
            ];
        }

        $isYouTube = false;
        $videos = [];

        // Check if YouTube API is enabled and configured
        if ($this->isYouTubeApiEnabled()) {
            $videos = $this->fetchYouTubeVideos($limit);
            if (! empty($videos)) {
                $isYouTube = true;
            }
        }

        // If no YouTube videos (disabled, empty, or failed), fallback to default database videos
        if (empty($videos)) {
            $videos = $this->getDefaultVideos($limit);
            $isYouTube = false;
        }

        $lead = ! empty($videos) ? $videos[0] : null;
        $secondary = count($videos) > 1 ? array_slice($videos, 1, 3) : [];

        return [
            'enabled' => true,
            'is_youtube' => $isYouTube,
            'title' => $title,
            'lead' => $lead,
            'secondary' => $secondary,
        ];
    }
}
