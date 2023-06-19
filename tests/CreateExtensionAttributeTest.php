<?php

use LapSafe\ExtensionAttributes\Facades\ExtensionAttributes;
use LapSafe\ExtensionAttributes\Models\ExtensionAttribute;

it('can create an extension attribute', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String, 'testing_123');

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('testing_123')
        ->and($attribute->model_type)->toBe(array_flip(config('extension-attributes.models'))[ExtensionAttribute::class])
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\Enums\AttributeType::String);
});

it('converts key to slug', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String, 'My-Special Key');

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('my_special_key')
        ->and($attribute->model_type)->toBe(array_flip(config('extension-attributes.models'))[ExtensionAttribute::class])
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\Enums\AttributeType::String);
});

it('can automatically generate key based upon name', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String);

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('test')
        ->and($attribute->model_type)->toBe(array_flip(config('extension-attributes.models'))[ExtensionAttribute::class])
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\Enums\AttributeType::String);
});

it('ensures that key is unique to model', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String);
    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String);

})->throws(\LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeKeyAlreadyExistsException::class);

it('can accept the same key for different models', function () {
    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String);
    ExtensionAttributes::new('test', \LapSafe\ExtensionAttributes\Tests\TestModels\User::class, \LapSafe\ExtensionAttributes\Enums\AttributeType::String);
})->throwsNoExceptions();
