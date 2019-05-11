<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use TCB\Flysystem\Files;
use TCB\Flysystem\Sync;

class Execute extends AbstractCommand
{
    protected $signature = 'project:execute {path}';

    protected $description = 'Execute a project. Run all syncs for it.';

    public function handle(): void
    {
        $project = $this->argument('path');

        [$sources, $backups] = (new Files)->getConfig($project);

        foreach ($backups as $bkup) {

            foreach ($sources as $src) {

                $this->sync($src, $bkup);
            }
        }
    }

    private function sync(AdapterInterface $source, AdapterInterface $backup): void
    {
        $backup = new Filesystem($backup);
        $source = new Filesystem($source);

        /** @var Sync $sync */
        $sync = new Sync($source, $backup);
        $sync->sync();
    }
}