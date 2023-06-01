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
    public array $models = [];

    /**
     * @param  class-string<Model>  $model
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

        if (! in_array($model, $this->models)) {
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

    /**
     * @param  array<class-string>  $models
     */
    public function registerModels(array $models): void
    {
        $this->models = $models;
    }

    protected function generateKey(string $name): string
    {
        return Str::slug($name, '_');
    }
}
