<?php

namespace LapSafe\ExtensionAttributes\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use LapSafe\ExtensionAttributes\ExtensionAttributesServiceProvider;
use LapSafe\ExtensionAttributes\Models\ExtensionAttribute;
use LapSafe\ExtensionAttributes\Tests\TestModels\User;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LapSafe\\ExtensionAttributes\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ExtensionAttributesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('extension-attributes.models', [
            'user' => User::class,
            'extension_attribute' => ExtensionAttribute::class,
        ]);

        $migration = include __DIR__.'/../database/migrations/create_extension_attributes_table.php.stub';
        $migration->up();
    }
}
