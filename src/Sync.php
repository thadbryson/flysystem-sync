<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem as BaseFilesystem;
use TCB\FlysystemSync\Filesystem;

class Sync
{
    public readonly Collection $items;

    public function __construct()
    {
        $this->items = new Collection;
    }

    public function runner(BaseFilesystem $reader, BaseFilesystem $writer): Action\Runner
    {
        $sources = Filesystem\Helper::loadAllPaths($reader, $this->items->all());    // Load all set paths
        $targets = Filesystem\Helper::loadAllPaths($writer, $sources);               // Find matching targets

        return new Action\Runner($reader, $writer, $sources, $targets);
    }
}
