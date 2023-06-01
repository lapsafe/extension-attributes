<?php

namespace LapSafe\ExtensionAttributes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExtensionAttributesServiceProvider extends PackageServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ExtensionAttributeRegistrar::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(name: 'extension-attributes')
            ->hasConfigFile()
            ->hasMigration(migrationFileName: 'create_extension_attributes_table');
    }
}
