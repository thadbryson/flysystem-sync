<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands\Project;

use TCB\Flysystem\Commands\AbstractCommand;

class Execute extends AbstractCommand
{
    protected $signature = 'project:execute {name} {saveDirectory}';

    protected $description = 'Execute a project. Run all syncs for it.';

    public function handle(): void
    {

    }
}