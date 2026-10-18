<?php

if (! function_exists('nepali_number')) {
    /**
     * Convert English digits (0-9) to Nepali Devanagari digits (०-९).
     * Handles integers, floats, numeric strings, and strings containing numbers.
     */
    function nepali_number(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $digits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        return str_replace(range(0, 9), $digits, (string) $value);
    }
}

if (! function_exists('nepali_date')) {
    /**
     * Format a date into Bikram Sambat Nepali date string.
     */
    function nepali_date(\Carbon\CarbonInterface|\DateTimeInterface|string|null $date = null): string
    {
        if (! $date) {
            $date = now();
        } elseif (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return \App\Models\Article::formatNepaliDate($date);
    }
}

if (! function_exists('setting')) {
    /**
     * Retrieve a website setting value with an optional fallback default.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('setting_bool')) {
    /**
     * Retrieve a boolean website setting value with an optional fallback default.
     */
    function setting_bool(string $key, bool $default = false): bool
    {
        return \App\Models\Setting::getBool($key, $default);
    }
}

if (! function_exists('setting_image_url')) {
    /**
     * Resolve a public URL for an image stored in settings, uploaded files, or assets.
     */
    function setting_image_url(?string $path, ?string $default = null): ?string
    {
        $target = $path ?: $default;
        if (blank($target)) {
            return null;
        }

        if (str_starts_with($target, 'http://') || str_starts_with($target, 'https://')) {
            return $target;
        }

        if (str_starts_with($target, 'storage/')) {
            return asset($target);
        }

        if (str_starts_with($target, 'images/') || file_exists(public_path($target))) {
            return asset($target);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($target)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($target);
        }

        return asset('storage/' . ltrim($target, '/'));
    }
}

if (! function_exists('nepali_format_amount')) {
    /**
     * Format a number using the South Asian/Nepali comma numbering system (e.g., 1,37,14,141.90).
     */
    function nepali_format_amount(float|int|string|null $number, int $decimals = 2): string
    {
        if ($number === null || $number === '') {
            return '0.00';
        }
        $num = (float) $number;
        $isNegative = $num < 0;
        $num = abs($num);

        $parts = explode('.', number_format($num, $decimals, '.', ''));
        $intPart = $parts[0];
        $decPart = isset($parts[1]) ? '.' . $parts[1] : '';

        if (strlen($intPart) > 3) {
            $lastThree = substr($intPart, -3);
            $remaining = substr($intPart, 0, -3);
            $remainingFormatted = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
            $intPart = $remainingFormatted . ',' . $lastThree;
        }

        return ($isNegative ? '-' : '') . $intPart . ($decimals > 0 ? $decPart : '');
    }
}
