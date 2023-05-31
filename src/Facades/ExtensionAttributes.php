<?php

namespace LapSafe\ExtensionAttributes\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LapSafe\ExtensionAttributes\ExtensionAttributes
 */
class ExtensionAttributes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LapSafe\ExtensionAttributes\ExtensionAttributes::class;
    }
}
