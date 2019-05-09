<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands\Init;

use Santa\Commands\AbstractCommand;

class InitConfig extends AbstractCommand
{
    protected $signature = 'make:config {filepath}';

    protected $description = 'Make a new Sync configuration file.';

    public function handle(): void
    {
        $filepath = $this->argument('filepath');
    }
}