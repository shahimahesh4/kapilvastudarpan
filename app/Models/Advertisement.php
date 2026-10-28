<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    public const PLACEMENTS = [
        'header' => 'Header Banner (Beside Logo - 728x90)',
        'home_top' => 'Home Top Banner (970x100 / 970x250)',
        'home_middle' => 'Home Middle Banner (970x100)',
        'sidebar' => 'Sidebar Banner (300x250 / 300x600)',
        'article_incontent' => 'Article In-content Banner',
        'footer' => 'Footer Banner',
    ];

    protected $fillable = [
        'title',
        'placement',
        'type',
        'image_path',
        'target_url',
        'code_snippet',
        'is_active',
        'starts_at',
        'ends_at',
        'impressions',
        'clicks',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('order')
            ->orderByDesc('id');
    }

    public function scopePlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement);
    }

    public function recordImpression(): void
    {
        $this->increment('impressions');
    }

    public function recordClick(): void
    {
        $this->increment('clicks');
    }

    public function imageUrl(int $width = 970): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'images/ads/')) {
            return route('images.optimized', [
                'path' => $this->image_path,
                'w' => $width,
                'v' => $this->updated_at?->getTimestamp() ?? 0,
            ]);
        }

        if (str_starts_with($this->image_path, 'images/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->imageUrl();
    }
}
