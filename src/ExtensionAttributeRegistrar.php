<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExtensionAttributeRegistrar
{
    protected Repository $cache;

    protected Collection|null $attributes = null;

    public \DateInterval|int $cacheExpirationTime;

    public string $cacheKey;

    public function __construct(protected CacheManager $cacheManager)
    {
        $this->initialiseCache();
    }

    public function initialiseCache(): void
    {
        $this->cacheExpirationTime = config('extension-attributes.cache.expiration_time') ?: \DateInterval::createFromDateString('24 hours');
        $this->cacheKey = config('extension-attributes.cache.key');
        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        // the 'default' fallback here is from the permission.php config file,
        // where 'default' means to use config(cache.default)
        $cacheDriver = config('extension-attributes.cache.store', 'default');

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (! array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    public function forgetCachedAttributes(): void
    {
        $this->cache->forget($this->cacheKey);
    }

    private function loadAttributes(): void
    {
        if ($this->attributes !== null) {
            return;
        }

        $this->attributes = collect($this->cache->remember(
            $this->cacheKey, $this->cacheExpirationTime, fn () => $this->getSerializedAttributesForCache()
        ));
    }

    private function getSerializedAttributesForCache(): array
    {
        return ExtensionAttribute::query()->get()->map(function (ExtensionAttribute $attribute) {
            return [
                'key' => $attribute->key,
                'name' => $attribute->name,
                'type' => $attribute->type->value,
                'model_type' => $attribute->model_type,
            ];
        })->toArray();
    }

    /**
     * @param  class-string<Model>|null  $model
     */
    public function getAttributes(?string $model = null, ?string $key = null): Collection|array|null
    {
        $this->loadAttributes();

        return is_null($key)
            ? $this->attributes
            : $this->attributes->where('key', $key)->where('model_type', (new $model)->getMorphClass())->first();
    }
}
