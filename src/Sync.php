<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;

class Sync
{


    public function __construct()
    {
    }

    public function getSorter(Filesystem $reader, Filesystem $writer, Factory $factory = null): void
    {
        $sources = new Collection($reader);
        $sources->load();

        $targets = new Collection($writer, ...$sources->paths());

        $sources->loadNew(...$targets->paths());

        $factory = $factory ?? new Factory;
        $factory->setFilesystems($reader, $writer);

        $sorter = new Sorter($factory, $sources, $targets);
    }
}
