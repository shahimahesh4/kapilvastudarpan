<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Article $article) {
            // Slugs remain in storage only to resolve legacy URLs; public URLs use date and ID.
            if (blank($article->slug)) {
                $baseSlug = Str::slug((string) $article->title) ?: 'article';
                $slug = $baseSlug;
                $suffix = 2;

                $slugIsTaken = function (string $candidate) use ($article): bool {
                    $query = static::withTrashed()->where('slug', $candidate);

                    if ($article->exists) {
                        $query->whereKeyNot($article->getKey());
                    }

                    return $query->exists();
                };

                while ($slugIsTaken($slug)) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                $article->slug = $slug;
            }

            if (blank($article->meta_title)) {
                $article->meta_title = static::suggestedMetaTitle($article->title);
            }

            if (blank($article->meta_description)) {
                $article->meta_description = static::suggestedMetaDescription(
                    $article->excerpt,
                    $article->body,
                    $article->title,
                );
            }

            if (blank($article->meta_keywords)) {
                $article->meta_keywords = static::suggestedMetaKeywords($article->title);
            }

            if (empty($article->category_id) && $article->relationLoaded('categories') && $article->categories->isNotEmpty()) {
                $article->category_id = $article->categories->first()->id;
            }

            if (empty($article->user_id) && auth()->check()) {
                $article->user_id = auth()->id();
            }

            if (isset($article->is_published)) {
                $article->status = $article->is_published ? 'published' : 'draft';
            }
        });

        static::saved(function (Article $article) {
            if ($article->category_id) {
                $article->categories()->syncWithoutDetaching([$article->category_id]);
            }

            static::clearPortalCache();
        });

        static::deleted(fn () => static::clearPortalCache());
        static::restored(fn () => static::clearPortalCache());
    }

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'image_path',
        'source_url',
        'author',
        'province',
        'published_at',
        'is_published',
        'status',
        'is_featured',
        'is_breaking',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'views' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->latest();
    }

    public function approvedRootComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with(['user', 'replies.user'])
            ->latest();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Limit listing queries to the fields needed by cards and navigation.
     * Full article bodies are loaded only on the article detail page.
     */
    public function scopeForCard(Builder $query): Builder
    {
        return $query
            ->select([
                'id', 'category_id', 'title', 'slug', 'excerpt', 'image_path', 'author',
                'province', 'published_at', 'is_featured', 'is_breaking', 'views', 'updated_at',
            ])
            ->with([
                'category:id,name,slug',
                'categories:id,name,slug',
            ]);
    }

    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'status' => 'published',
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
            'status' => 'draft',
        ]);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->orderByDesc('views');
    }

    public function scopeForCategory(Builder $query, string $categorySlug): Builder
    {
        return $query->where(function (Builder $q) use ($categorySlug) {
            $q->whereHas('categories', fn (Builder $sub) => $sub->where('slug', $categorySlug))
                ->orWhereHas('category', fn (Builder $sub) => $sub->where('slug', $categorySlug));
        });
    }

    public function incrementViews(): void
    {
        // A page view must not invalidate the cached homepage/news lists.
        // Update the counter directly and keep the in-memory model accurate.
        static::query()->whereKey($this->getKey())->increment('views');
        $this->views++;
    }

    /** The canonical date-and-ID permalink used for public article pages. */
    public function publicUrl(): string
    {
        $date = ($this->published_at ?? $this->created_at ?? now())
            ->copy()
            ->timezone(config('app.timezone'));

        return route('articles.show', [
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
            'day' => $date->format('d'),
            'article' => $this->getKey(),
        ]);
    }

    public static function suggestedMetaTitle(?string $title): string
    {
        $headline = trim((string) $title);
        $siteName = (string) setting('site_name', 'कपिलवस्तु दर्पण');

        return Str::limit($headline.' | '.$siteName, 70, '');
    }

    public static function suggestedMetaDescription(?string $excerpt, ?string $body, ?string $title): string
    {
        $content = $excerpt ?: $body ?: $title;

        return Str::limit(Str::squish(strip_tags((string) $content)), 160, '');
    }

    public static function suggestedMetaKeywords(?string $title): string
    {
        $headlineWords = preg_split('/\s+/u', strip_tags((string) $title)) ?: [];
        $keywords = collect($headlineWords)
            ->map(fn (string $word) => preg_replace('/^[\p{P}\p{S}]+|[\p{P}\p{S}]+$/u', '', trim($word)) ?? '')
            ->filter(fn (string $word) => Str::length($word) > 1)
            ->take(6)
            ->prepend('नेपाली समाचार')
            ->prepend('कपिलवस्तु दर्पण')
            ->unique()
            ->values();

        return $keywords->implode(', ');
    }

    /** Remove cached news lists after content changes. */
    public static function clearPortalCache(): void
    {
        Cache::forget('portal.homepage.v1');
        Cache::forget('portal.header-news.v1');
        Cache::store('file')->forget('portal.homepage.v2');
        Cache::store('file')->forget('portal.header-news.v2');
    }

    public function getEstimatedReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 180));
    }

    public function getNepaliReadingTimeAttribute(): string
    {
        return nepali_number($this->estimated_reading_time);
    }

    public function getNepaliViewsAttribute(): string
    {
        return nepali_number(number_format((int) $this->views));
    }

    public function getImageUrlAttribute(): string
    {
        return $this->imageUrl();
    }

    /**
     * Return a size-appropriate image rendition for local uploads.
     */
    public function imageUrl(int $width = 1280): string
    {
        if (empty($this->image_path)) {
            return asset('images/kapilvastu-darpan-logo.jpg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'articles/') || str_starts_with($this->image_path, 'media/')) {
            return route('images.optimized', ['path' => $this->image_path, 'w' => $width]);
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }

        return asset($this->image_path);
    }

    public function getAuthorAttribute(?string $value): ?string
    {
        if (app()->bound('request') && request()?->is('admin*')) {
            return $value;
        }

        return static::formatAuthorName($value);
    }

    public function getDisplayAuthorAttribute(): string
    {
        return static::formatAuthorName($this->attributes['author'] ?? null);
    }

    public static function formatAuthorName(?string $rawAuthor): string
    {
        return 'कपिलवस्तु दर्पण';
    }

    public function getNepaliDateAttribute(): string
    {
        if (! $this->published_at) {
            return 'शुक्रबार, ३० असोज २०८३';
        }

        return static::formatNepaliDate($this->published_at);
    }

    public static function formatNepaliDate(CarbonInterface $date): string
    {
        $days = ['आइतबार', 'सोमबार', 'मंगलबार', 'बुधबार', 'बिहीबार', 'शुक्रबार', 'शनिबार'];
        $months = [
            1 => 'वैशाख', 2 => 'जेठ', 3 => 'असार', 4 => 'साउन',
            5 => 'भदौ', 6 => 'असोज', 7 => 'कात्तिक', 8 => 'मंसिर',
            9 => 'पुस', 10 => 'माघ', 11 => 'फागुन', 12 => 'चैत',
        ];
        $digits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];

        $base = Carbon::create(2026, 4, 14, 0, 0, 0, 'Asia/Kathmandu');
        $diffDays = (int) $base->diffInDays($date, false);

        $monthDays = [
            1 => 31, 2 => 31, 3 => 32, 4 => 31,
            5 => 31, 6 => 31, 7 => 30, 8 => 29,
            9 => 30, 10 => 29, 11 => 30, 12 => 30,
        ];

        $year = 2083;
        $month = 1;
        $day = 1;

        if ($diffDays >= 0) {
            $rem = $diffDays;
            for ($m = 1; $m <= 12; $m++) {
                if ($rem < $monthDays[$m]) {
                    $month = $m;
                    $day = $rem + 1;
                    break;
                }
                $rem -= $monthDays[$m];
            }
        } else {
            return 'शुक्रबार, ३० असोज २०८३';
        }

        $dayName = $days[$date->dayOfWeek];
        $dayNepali = str_replace(range(0, 9), $digits, (string) $day);
        $yearNepali = str_replace(range(0, 9), $digits, (string) $year);
        $monthName = $months[$month];

        return "{$dayName}, {$dayNepali} {$monthName} {$yearNepali}";
    }
}
