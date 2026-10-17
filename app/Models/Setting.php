<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'app_site_settings_all';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key with optional fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAll();

        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return $all[$key];
        }

        return $default;
    }

    /**
     * Get a boolean setting value with optional fallback.
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $val = static::get($key, null);

        if ($val === null) {
            return $default;
        }

        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, ?string $group = null): static
    {
        if (is_bool($value)) {
            $storedValue = $value ? '1' : '0';
        } elseif (is_array($value)) {
            $storedValue = json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $storedValue = $value;
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'group' => $group,
            ]
        );

        static::clearCache();

        return $setting;
    }

    /**
     * Set multiple settings at once.
     *
     * @param  array<string, mixed>  $settings
     */
    public static function setMany(array $settings, ?string $group = null): void
    {
        foreach ($settings as $key => $value) {
            if (is_bool($value)) {
                $storedValue = $value ? '1' : '0';
            } elseif (is_array($value)) {
                $storedValue = json_encode($value, JSON_UNESCAPED_UNICODE);
            } else {
                $storedValue = $value;
            }

            static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $storedValue,
                    'group' => $group,
                ]
            );
        }

        static::clearCache();
    }

    /**
     * Get all settings as key => value array from cache.
     *
     * @return array<string, mixed>
     */
    public static function getAll(): array
    {
        return Cache::rememberForever(static::CACHE_KEY, function () {
            $raw = static::query()->pluck('value', 'key')->toArray();
            $result = [];

            foreach ($raw as $key => $val) {
                if (is_string($val) && (str_starts_with($val, '[') || str_starts_with($val, '{'))) {
                    $decoded = json_decode($val, true);
                    $result[$key] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;
                } else {
                    $result[$key] = $val;
                }
            }

            return $result;
        });
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
