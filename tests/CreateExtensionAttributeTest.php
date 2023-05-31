<?php

use LapSafe\ExtensionAttributes\ExtensionAttribute;
use LapSafe\ExtensionAttributes\Facades\ExtensionAttributes;

it('can create an extension attribute', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String, 'testing-123');

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('testing-123')
        ->and($attribute->model_type)->toBe((new ExtensionAttribute)->getMorphClass())
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
});

it('converts key to slug', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String, 'My_Special Key');

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('my-special-key')
        ->and($attribute->model_type)->toBe((new ExtensionAttribute)->getMorphClass())
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
});

it('can automatically generate key based upon name', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String);

    $attribute = ExtensionAttribute::query()->first();

    expect($attribute)->not->toBeNull()
        ->and($attribute->name)->toBe('test')
        ->and($attribute->key)->toBe('test')
        ->and($attribute->model_type)->toBe((new ExtensionAttribute)->getMorphClass())
        ->and($attribute->type)->toBe(\LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
});

it('ensures that key is unique to model', function () {
    expect(ExtensionAttribute::query()->exists())->toBeFalse();

    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String);

})->throws(\LapSafe\ExtensionAttributes\Exceptions\ExtensionAttributeKeyAlreadyExistsException::class);

it('can accept the same key for different models', function () {
    ExtensionAttributes::new('test', ExtensionAttribute::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
    ExtensionAttributes::new('test', \LapSafe\ExtensionAttributes\Tests\TestModels\User::class, \LapSafe\ExtensionAttributes\ExtensionAttributeType::String);
})->throwsNoExceptions();
