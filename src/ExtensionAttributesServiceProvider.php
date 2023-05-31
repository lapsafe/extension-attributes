<?php

namespace LapSafe\ExtensionAttributes;

use LapSafe\ExtensionAttributes\Commands\ExtensionAttributesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExtensionAttributesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('extension-attributes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_extension-attributes_table')
            ->hasCommand(ExtensionAttributesCommand::class);
    }
}
