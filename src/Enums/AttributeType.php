<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes\Enums;

use Illuminate\Support\Carbon;

enum AttributeType: string
{
    case String = 'string';
    case Integer = 'integer';
    case Date = 'date';
    case DateTime = 'datetime';

    public function cast(mixed $value): mixed
    {
        return match ($this) {
            self::String => (string) $value,
            self::Integer => (int) $value,
            self::Date, self::DateTime => Carbon::parse($value),
        };
    }

    public function validationRules(): array
    {
        return match ($this) {
            self::String => ['string', 'max:64'],
            self::Integer => ['integer'],
            self::Date, self::DateTime => ['date'],
        };
    }
}
