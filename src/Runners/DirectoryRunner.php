<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Runners\Contracts\Runner as RunnerContract;

class DirectoryRunner implements RunnerContract
{
    protected readonly Filesystems\Extended $reader;

    protected readonly Filesystems\Extended $writer;

    public function __construct(Filesystems\Extended $reader, Filesystems\Extended $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function create(Directory $source): void
    {
        $this->writer->createDirectory($source->path);
    }

    public function update(Directory $source): void
    {
        $this->writer->createDirectory($source->path);
    }

    public function delete(Directory $target): void
    {
        $this->writer->deleteDirectory($target->path);
    }
}
