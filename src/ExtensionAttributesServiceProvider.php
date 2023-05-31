<?php

namespace LapSafe\ExtensionAttributes;

use LapSafe\ExtensionAttributes\Commands\ExtensionAttributesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExtensionAttributesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name(name: 'extension-attributes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration(migrationFileName: 'create_extension_attributes_table')
            ->hasCommand(commandClassName: ExtensionAttributesCommand::class);
    }
}
