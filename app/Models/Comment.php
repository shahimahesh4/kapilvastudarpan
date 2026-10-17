<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the article that owns the comment.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user that authored the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment if this is a reply.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the approved replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('is_approved', true)->oldest();
    }

    /**
     * Get all replies regardless of approval status.
     */
    public function allReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    /**
     * Scope a query to only include approved comments.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include top-level root comments.
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include replies.
     */
    public function scopeReplies(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Formatted Nepali relative time.
     */
    public function getNepaliTimeAttribute(): string
    {
        $diff = $this->created_at?->diffForHumans() ?? '';
        return nepali_number($diff);
    }

    /**
     * Formatted Nepali date string.
     */
    public function getNepaliDateAttribute(): string
    {
        return nepali_date($this->created_at);
    }
}
