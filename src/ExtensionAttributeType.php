<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes;

enum ExtensionAttributeType: string
{
    case String = 'string';
    case Integer = 'integer';
    case Date = 'date';
    case DateTime = 'datetime';
}
