<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes;

use Illuminate\Support\Carbon;

enum ExtensionAttributeType: string
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
}
