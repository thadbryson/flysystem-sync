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
        public ReaderFilesystem $reader,
        public Filesystem $writer,
        public DirectoryAttributes $directory
    ) {
        $this->path = $this->directory->path();
    }

    public function type(): string
    {
        return 'directory';
    }

    protected function readerExists(): bool
    {
        return  $this->reader->directoryExists($this->path);
    }

    protected function writerExists(): bool
    {
        return $this->writer->directoryExists($this->path);
    }
}
