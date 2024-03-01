<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

trait DirectoryTrait
{
    use ActionTrait;

    public string $path;

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

    public function isReaderExistingValid(): bool
    {
        return $this->isOnReader() === $this->reader->directoryExists($this->path);
    }

    public function isWriterExistingValid(): bool
    {
        return $this->isOnWriter() === $this->writer->directoryExists($this->path);
    }

    public function isExpected(): bool
    {
        return $this->writer->directoryExists($this->path) && $this->getDifferences() === [];
    }
}
