<?php

namespace LapSafe\ExtensionAttributes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeKeyAlreadyExistsException;

class ExtensionAttributes
{
    /**
     * @param  class-string<Model>  $model
     *
     * @throws ExtensionAttributeKeyAlreadyExistsException
     */
    public function new(
        string $name,
        string $model,
        ExtensionAttributeType $attributeType,
        ?string $key = null
    ): ExtensionAttribute {
        $key = $this->generateKey(name: $key ?? $name);

        if (! is_null(app(ExtensionAttributeRegistrar::class)->getAttributes(model: $model, key: $key))) {
            throw ExtensionAttributeKeyAlreadyExistsException::make(model: $model, key: $key);
        }

        $attribute = ExtensionAttribute::query()->create([
            'name' => $name,
            'key' => $key,
            'model_type' => (new $model)->getMorphClass(),
            'type' => $attributeType,
        ]);

        app(ExtensionAttributeRegistrar::class)->forgetCachedAttributes();

        return $attribute;
    }

    protected function generateKey(string $name): string
    {
        return Str::slug($name, '_');
    }
}
