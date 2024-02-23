<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Paths\Collection;
use TCB\FlysystemSync\Runners\Runner;

/**
 * @todo
 *      - Logging
 *      - Exception handling with logging
 *      - CLI system
 */
class Sync
{
    public readonly Collection $items;

    public array $log = [];

    public function __construct()
    {
        $this->items = new Collection;
    }

    public function sync(FilesystemAdapter $reader, FilesystemAdapter $writer): array
    {
        $runner = new Runner(
            $reader,
            $writer,
            $this->items->getFiles(),
            $this->items->getDirectories()
        );

        $this->log = [
            'creates' => $runner->runCreates(),
            'updates' => $runner->runUpdates(),
            'deletes' => $runner->runDeletes(),
        ];
    }
}
