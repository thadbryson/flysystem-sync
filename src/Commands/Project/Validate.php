<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands\Project;

use TCB\Flysystem\Commands\AbstractCommand;

class Validate extends AbstractCommand
{
    protected $signature = 'project:validate {path}';

    protected $description = 'Validate a project configuration file.';

    public function handle(): void
    {

    }
}