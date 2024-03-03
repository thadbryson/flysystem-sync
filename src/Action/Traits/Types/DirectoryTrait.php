<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

trait DirectoryTrait
{
    use ActionTrait;

    public function __construct(
        public readonly ReaderFilesystem $reader,
        public readonly Filesystem $writer,
        DirectoryAttributes $directory
    ) {
        $this->path     = $directory;
        $this->location = $directory->path();
    }

    protected function readerExists(): bool
    {
        return $this->reader->directoryExists($this->location);
    }

    protected function writerExists(): bool
    {
        return $this->writer->directoryExists($this->location);
    }
}
