<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\FilesystemAdapter;

class Sync
{
    public readonly Collection $paths;

    public function __construct()
    {
        $this->paths = new Collection;
    }

    public function sync(
        LeagueFilesystem|FilesystemAdapter $reader,
        LeagueFilesystem|FilesystemAdapter $writer
    ): Runner\Runner {
        $sources = Filesystem\HelperFilesystem::loadAllPaths($reader, $this->paths->all());    // Load all set paths
        $targets = Filesystem\HelperFilesystem::loadAllPaths($writer, $sources);               // Find matching targets

        return new Runner\Runner($reader, $writer, $sources, $targets);
    }
}
