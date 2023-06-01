<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes\Exceptions;

class ModelHasNotBeenRegistered extends \Exception
{
    public static function make(string $model): self
    {
        return new self("Model {$model} has not been registered for extension attributes");
    }
}
