<?php

namespace LapSafe\ExtensionAttributes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeKeyAlreadyExistsException;

class ExtensionAttributes
{
    /**
     * @param string $name
     * @param class-string<Model> $model
     * @param ExtensionAttributeType $attributeType
     * @param string|null $key
     * @return ExtensionAttribute
     * @throws ExtensionAttributeKeyAlreadyExistsException
     */
    public function new(
        string $name,
        string $model,
        ExtensionAttributeType $attributeType,
        ?string $key = null
    ): ExtensionAttribute {
        $key = $this->generateKey(name: $key ?? $name);

        if ($this->exists(model: $model, key: $key)) {
            throw ExtensionAttributeKeyAlreadyExistsException::make(model: $model, key: $key);
        }

        return ExtensionAttribute::query()->create([
            'name' => $name,
            'key' => $key,
            'model_type' => (new $model)->getMorphClass(),
            'type' => $attributeType,
        ]);
    }

    protected function generateKey(string $name): string
    {
        return Str::slug($name, '_');
    }

    public function exists(string $model, string $key): bool
    {
        return ExtensionAttribute::query()
            ->where('key', $key)
            ->where('model_type', $model)
            ->exists();
    }

    public function find(string $model, string $key): ?ExtensionAttribute
    {
        return ExtensionAttribute::query()
            ->where('key', $key)
            ->where('model_type', $model)
            ->first();
    }
}
