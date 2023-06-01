<?php

namespace LapSafe\ExtensionAttributes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LapSafe\ExtensionAttributes\Enums\AttributeType;
use LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeKeyAlreadyExistsException;
use LapSafe\ExtensionAttributes\Exceptions\ModelHasNotBeenRegistered;
use LapSafe\ExtensionAttributes\Models\ExtensionAttribute;

class ExtensionAttributes
{
    /**
     * @param class-string<Model> $model
     *
     * @throws ExtensionAttributeKeyAlreadyExistsException
     * @throws ModelHasNotBeenRegistered
     */
    public function new(
        string $name,
        string $model,
        AttributeType $attributeType = AttributeType::String,
        ?string $key = null
    ): ExtensionAttribute {
        $key = $this->generateKey(name: $key ?? $name);

        if (! is_null(app(ExtensionAttributeRegistrar::class)->getAttributes(model: $model, key: $key))) {
            throw ExtensionAttributeKeyAlreadyExistsException::make(model: $model, key: $key);
        }

        if(! in_array($model, config('extension-attributes.models'))) {
            throw ModelHasNotBeenRegistered::make(model: $model);
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
