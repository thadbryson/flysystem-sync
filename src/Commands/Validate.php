<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands;

use TCB\Flysystem\Files;

class Validate extends AbstractCommand
{
    protected $signature = 'project:validate {path}';

    protected $description = 'Validate a project configuration file.';

    public function handle(): void
    {
        // Load project config.
        $project = $this->argument('path');

        [$sources, $backups] = (new Files)->getConfig($project);

        $this->info('Project configuration is VALID');
        $this->line('');
        $this->line('Number of backups: ' . count($backups));
        $this->line('Number of sources: ' . count($sources));
    }
}
