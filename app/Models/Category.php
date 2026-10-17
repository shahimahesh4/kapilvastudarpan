<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'order', 'is_nav', 'meta_title', 'meta_description', 'meta_keywords'];

    protected function casts(): array
    {
        return [
            'is_nav' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category')->withTimestamps();
    }

    public function scopeNav(Builder $query): Builder
    {
        return $query->where('is_nav', true)->orderBy('order')->orderBy('id');
    }
}
