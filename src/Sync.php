<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Collections\PathCollection;
use TCB\FlysystemSync\Collections\Sorter;
use TCB\FlysystemSync\Filesystem\Writer;

class Sync
{
    protected readonly PathCollection $paths;

    public Factory $factory;

    public function __construct(FilesystemAdapter $reader)
    {
        $this->paths   = new PathCollection($reader);
        $this->factory = new Factory;
    }

    public function files(string ...$files): static
    {
        $this->paths->set(true, ...$files);

        return $this;
    }

    public function directories(string ...$directories): static
    {
        $this->paths->set(false, ...$directories);

        return $this;
    }

    public function paths(string ...$paths): static
    {
        $this->paths->set(null, ...$paths);

        return $this;
    }

    public function sync(FilesystemAdapter $writer): void
    {
        $sources = $this->paths->all();
        $targets = $this->paths->loadNew($writer)->all();

        $writer = new Writer($writer);

        $bag = new Sorter($sources, $targets, $this->factory);

        foreach (array_merge(
            $bag->create_directories,
            $bag->delete_directories,
            $bag->update_directories,
            $bag->nothing_directories,
            $bag->create_files,
            $bag->delete_files,
            $bag->update_files,
            $bag->nothing_files
        ) as $action) {
            $action->execute($this->paths->reader, $writer);
        }
    }
}
