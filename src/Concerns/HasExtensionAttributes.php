<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes\Concerns;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;
use LapSafe\ExtensionAttributes\Enums\AttributeType;
use LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeNotFound;
use LapSafe\ExtensionAttributes\ExtensionAttributeRegistrar;

/**
 * @property Collection $extension_attributes
 */
trait HasExtensionAttributes
{
    public function initializeHasExtensionAttributes(): void
    {
        $this->casts = array_merge($this->casts, [
            'extension_attributes' => AsCollection::class,
        ]);
    }

    public function setExtensionAttribute(string $key, mixed $value): void
    {
        if(is_null($this->extension_attributes)) {
            $this->extension_attributes = [];
        }

        $attribute = app(ExtensionAttributeRegistrar::class)->getAttributes(get_class($this), $key);
        $this->extension_attributes[$key] = AttributeType::from($attribute['type'])->cast($value);
    }

    public function getExtensionAttributeName(string $key): string
    {
        try {
            return $this->findAttribute($key)['name'];
        } catch (ExtensionAttributeNotFound) {
            return 'Unknown Attribute';
        }
    }

    /**
     * @throws ExtensionAttributeNotFound
     */
    protected function findAttribute(string $key): array
    {
        $attribute = app(ExtensionAttributeRegistrar::class)->getAttributes(get_class($this), $key);

        if (is_null($attribute)) {
            throw new ExtensionAttributeNotFound("Extension attribute {$key} not found on model {$this->getMorphClass()}");
        }

        return $attribute;
    }
}
