<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MetadataCache
{
    public static function categories(): Collection
    {
        return self::rememberCollection(
            'web3dshare.categories.v4',
            config('web3dshare.cache.categories_ttl'),
            fn () => Category::orderBy('name')->get(['id', 'name']),
            ['id', 'name']
        );
    }

    public static function popularTags(): Collection
    {
        return self::rememberCollection(
            'web3dshare.tags.popular.v4',
            config('web3dshare.cache.tags_ttl'),
            fn () => Tag::orderBy('name')->limit(10)->get(['id', 'name', 'slug']),
            ['id', 'name', 'slug']
        );
    }

    public static function forgetCategories(): void
    {
        self::cache()->forget('web3dshare.categories.v4');
    }

    public static function forgetPopularTags(): void
    {
        self::cache()->forget('web3dshare.tags.popular.v4');
    }

    private static function rememberCollection(string $key, int $ttl, callable $loader, array $requiredFields): Collection
    {
        $cache = self::cache();
        $value = $cache->get($key);

        if (self::isValidCollection($value, $requiredFields)) {
            return $value;
        }

        $cache->forget($key);
        $value = $loader();
        $cache->put($key, $value, now()->addSeconds($ttl));

        return $value;
    }

    private static function isValidCollection(mixed $value, array $requiredFields): bool
    {
        if (! $value instanceof Collection) {
            return false;
        }

        return $value->every(function ($item) use ($requiredFields) {
            if (! is_object($item)) {
                return false;
            }

            foreach ($requiredFields as $field) {
                if (! isset($item->{$field})) {
                    return false;
                }
            }

            return true;
        });
    }

    private static function cache()
    {
        return Cache::store(config('web3dshare.cache.store'));
    }
}
