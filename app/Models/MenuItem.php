<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    public const LOCATIONS = [
        'header' => 'Header Primary Navigation',
        'footer' => 'Footer Menu',
    ];

    public const TYPES = [
        'category' => 'Category',
        'page' => 'Page',
        'custom' => 'Custom URL',
    ];

    public const TARGETS = [
        '_self' => 'Same Tab (_self)',
        '_blank' => 'New Tab (_blank)',
    ];

    protected $fillable = [
        'title',
        'type',
        'category_id',
        'page_id',
        'url',
        'parent_id',
        'is_mega_menu',
        'location',
        'target',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_mega_menu' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('order')
            ->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('location', 'header');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function getLinkUrlAttribute(): string
    {
        if ($this->type === 'category' && $this->category) {
            return route('category.show', $this->category->slug);
        }

        if ($this->type === 'page' && $this->page) {
            return route('pages.show', $this->page->slug);
        }

        if (!empty($this->url)) {
            return $this->url;
        }

        return '#';
    }

    public function getIsExternalAttribute(): bool
    {
        return str_starts_with($this->link_url, 'http://') || str_starts_with($this->link_url, 'https://');
    }
}
