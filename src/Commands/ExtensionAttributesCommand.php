<?php

namespace LapSafe\ExtensionAttributes\Commands;

use Illuminate\Console\Command;

class ExtensionAttributesCommand extends Command
{
    public $signature = 'extension-attributes';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
