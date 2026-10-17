<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Rashifal extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'full_name',
        'english_name',
        'letters',
        'order',
        'element',
        'ruler',
        'lucky_number',
        'lucky_color',
        'lucky_direction',
        'lucky_day',
        'icon_index',
        'dainik',
        'saptahik',
        'masik',
        'varshik',
        'prediction_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'icon_index' => 'integer',
        'prediction_date' => 'date',
    ];

    /**
     * Scope for active records.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by zodiac sequence (0-11).
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Convert this model into the array format expected by the frontend templates.
     *
     * @return array<string, mixed>
     */
    public function toDisplayArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'full_name' => $this->full_name ?? ($this->name . ' राशि'),
            'english_name' => $this->english_name,
            'letters' => $this->letters,
            'index' => $this->icon_index ?? $this->order,
            'element' => $this->element,
            'ruler' => $this->ruler,
            'lucky_number' => $this->lucky_number,
            'lucky_color' => $this->lucky_color,
            'lucky_direction' => $this->lucky_direction,
            'lucky_day' => $this->lucky_day,
            'predictions' => [
                'dainik' => $this->dainik,
                'saptahik' => $this->saptahik,
                'masik' => $this->masik,
                'varshik' => $this->varshik,
            ],
        ];
    }
}
