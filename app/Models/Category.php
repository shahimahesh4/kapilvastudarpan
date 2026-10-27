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
        'default' => 'General — Diamond',
        'news' => 'Globe',
        'local' => 'Location',
        'politics' => 'Scales',
        'business' => 'Briefcase',
        'province' => 'Home',
        'society' => 'Leaf',
        'security' => 'Security diamond',
        'sports' => 'Star',
        'opinion' => 'Conversation',
        'health' => 'Health cross',
        'rashifal' => 'Sun',
        'education' => 'Education — Book',
        'technology' => 'Technology — Command',
        'science' => 'Science — Atom',
        'entertainment' => 'Entertainment — Star',
        'culture' => 'Culture — Music',
        'travel' => 'Travel — Plane',
        'weather' => 'Weather — Cloud',
        'agriculture' => 'Agriculture — Flower',
        'environment' => 'Environment — Recycle',
        'video' => 'Video — Play',
        'breaking' => 'Breaking — Exclamation',
        'national' => 'National — Flag',
        'world' => 'World — Globe',
        'economy' => 'Economy — Chart',
        'jobs' => 'Jobs — Badge',
        'law' => 'Law — Gavel',
        'crime' => 'Crime — Alert',
        'religion' => 'Religion — Temple',
        'literature' => 'Literature — Pen',
        'cinema' => 'Cinema — Clapper',
        'photo' => 'Photo — Lens',
        'podcast' => 'Podcast — Microphone',
        'live' => 'Live — Broadcast',
        'factcheck' => 'Fact check — Tick',
        'emergency' => 'Emergency — Warning',
        'diaspora' => 'Diaspora — People',
        'lifestyle' => 'Lifestyle — Heart',
        'market' => 'Market — Bar chart',
        'calendar' => 'Calendar',
        'games' => 'Games — Controller',
    ];

    private const DRAWER_ICON_PREVIEWS = [
        'default' => ['◆', '#7c4dff'], 'news' => ['◎', '#4cbfd0'], 'local' => ['⌖', '#3b82f6'],
        'politics' => ['⚖', '#4438d9'], 'business' => ['▰', '#4438d9'], 'province' => ['⌂', '#4b83f4'],
        'society' => ['♧', '#609c38'], 'security' => ['◇', '#71388c'], 'sports' => ['★', '#e6d117'],
        'opinion' => ['☏', '#77e1e8'], 'health' => ['+', '#ff5f66'], 'rashifal' => ['☼', '#ffab16'],
        'education' => ['▤', '#2563eb'], 'technology' => ['⌘', '#0ea5a4'], 'science' => ['⚛', '#6366f1'],
        'entertainment' => ['★', '#a855f7'], 'culture' => ['♬', '#db2777'], 'travel' => ['✈', '#0891b2'],
        'weather' => ['☁', '#60a5fa'], 'agriculture' => ['✿', '#65a30d'], 'environment' => ['♻', '#16a34a'],
        'video' => ['▶', '#e11d48'], 'breaking' => ['!', '#dc2626'],
        'national' => ['⚑', '#dc2626'], 'world' => ['◉', '#0284c7'], 'economy' => ['↗', '#15803d'],
        'jobs' => ['✦', '#7c3aed'], 'law' => ['⚖', '#1d4ed8'], 'crime' => ['!', '#b91c1c'],
        'religion' => ['⌂', '#d97706'], 'literature' => ['✎', '#a21caf'], 'cinema' => ['▣', '#be123c'],
        'photo' => ['◉', '#475569'], 'podcast' => ['♩', '#9333ea'], 'live' => ['●', '#e11d48'],
        'factcheck' => ['✓', '#16a34a'], 'emergency' => ['⚠', '#ea580c'], 'diaspora' => ['♙', '#0f766e'],
        'lifestyle' => ['♥', '#e11d48'],
        'market' => ['▮', '#4b83f4'], 'calendar' => ['▦', '#7c4dff'], 'games' => ['⌁', '#e6d117'],
    ];

    /** Trusted, static HTML labels for Filament's mobile-icon selector. */
    public static function drawerIconOptions(): array
    {
        return collect(self::DRAWER_ICONS)->map(function (string $label, string $key): string {
            [$glyph, $color] = self::DRAWER_ICON_PREVIEWS[$key];

            return sprintf(
                '<span style="display:inline-grid;width:1.75rem;height:1.75rem;margin-right:.55rem;place-items:center;border-radius:9999px;background:%s;color:#fff;font-family:Arial,sans-serif;font-size:1rem;font-weight:700;vertical-align:middle">%s</span><span>%s</span>',
                $color,
                e($glyph),
                e($label),
            );
        })->all();
    }

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
