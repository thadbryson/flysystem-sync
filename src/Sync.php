<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\ActionRunner;
use TCB\FlysystemSync\Filesystem\FilesystemHelper;

class Sync
{
    public readonly Collection $items;

    public function __construct()
    {
        $this->items = new Collection;
    }

    public function runner(Filesystem $reader, Filesystem $writer): ActionRunner
    {
        $sources = FilesystemHelper::loadAllPaths($reader, $this->items->all());    // Load all set paths
        $targets = FilesystemHelper::loadAllPaths($writer, $sources);               // Find matching targets

        return new ActionRunner($reader, $writer, $sources, $targets);
    }
}
