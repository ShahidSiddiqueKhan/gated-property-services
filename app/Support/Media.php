<?php

namespace App\Support;

class Media
{
    /**
     * Resolve a stored media path to a browser-usable URL.
     * Supports full external URLs (used for demo/seed data) as well as
     * local files stored on the "public" disk (php artisan storage:link).
     */
    public static function url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if ($path === '#') {
            return '#';
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
