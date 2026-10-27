<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    public const DRAWER_ICONS = [
        'news' => 'News — Globe',
        'local' => 'Local news — Location',
        'politics' => 'Politics — Scales',
        'business' => 'Business — Briefcase',
        'province' => 'Province — Home',
        'society' => 'Society — Leaf',
        'security' => 'Security — Camera',
        'sports' => 'Sports — Star',
        'opinion' => 'Opinion — Conversation',
        'health' => 'Health — Cross',
        'rashifal' => 'Horoscope — Sun',
    ];

    protected $fillable = ['name', 'slug', 'description', 'drawer_icon', 'order', 'is_nav', 'meta_title', 'meta_description', 'meta_keywords'];

    protected function casts(): array
    {
        return [
            'is_nav' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearPortalCache());
        static::deleted(fn () => static::clearPortalCache());
    }

    /** Remove category-dependent navigation and homepage data after changes. */
    protected static function clearPortalCache(): void
    {
        Cache::forget('portal.navigation.categories');
        Cache::forget('portal.homepage.v1');
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category')->withTimestamps();
    }

    public function directArticles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function allArticles(): Builder
    {
        return Article::query()->where(function ($q) {
            $q->where('category_id', $this->id)
                ->orWhereHas('categories', fn ($catQ) => $catQ->where('categories.id', $this->id));
        });
    }

    public function scopeNav(Builder $query): Builder
    {
        return $query->where('is_nav', true)->orderBy('order')->orderBy('id');
    }
}
