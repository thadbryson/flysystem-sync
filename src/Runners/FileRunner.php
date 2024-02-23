<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Runners\Contracts\Runner as RunnerContract;

class FileRunner implements RunnerContract
{
    protected readonly Filesystems\Extended $reader;

    protected readonly Filesystems\Extended $writer;

    public function __construct(Filesystems\Extended $reader, Filesystems\Extended $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function create(string $source): void
    {
        $this->writer->writeStream(
            $source,
            $this->reader->readStream($source)
        );
    }

    public function update(string $source): void
    {
        $this->writer->writeStream(
            $source,
            $this->reader->readStream($source)
        );
    }

    public function delete(string $target): void
    {
        $this->writer->delete($target);
    }
}
