<?php

namespace App\Models;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'image_path',
        'template',
        'is_published',
        'published_at',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'order' => 'integer',
            'views' => 'integer',
        ];
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function getCleanTitleAttribute(): string
    {
        return trim(preg_replace('/\s*\([A-Za-z\s&]+\)/', '', (string) $this->title));
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'pages/') || str_starts_with($this->image_path, 'articles/')) {
            return asset('storage/' . $this->image_path);
        }

        return asset($this->image_path);
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

    public function getNepaliDateAttribute(): string
    {
        return nepali_date($this->published_at ?? $this->created_at);
    }

    public function incrementViews(): void
    {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
    }

    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
        ]);
    }
}

