<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Article $article) {
            if (empty($article->category_id) && $article->relationLoaded('categories') && $article->categories->isNotEmpty()) {
                $article->category_id = $article->categories->first()->id;
            }

            if (isset($article->is_published)) {
                $article->status = $article->is_published ? 'published' : 'draft';
            }
        });
    }

    protected $fillable = [
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
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
        $this->increment('views');
    }

    public function getEstimatedReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));
        return max(1, (int) ceil($words / 180));
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/kapilvastu-darpan-logo.png');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'articles/')) {
            return asset('storage/' . $this->image_path);
        }

        return asset($this->image_path);
    }
}
