<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes\Exceptions;

use Exception;

class ExtensionAttributeKeyAlreadyExistsException extends Exception
{
    public static function make(string $model, string $key)
    {
        return new self("Extension attribute key ({$key}) already exists for {$model}");
    }
}
