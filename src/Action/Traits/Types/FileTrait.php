<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

trait FileTrait
{
    use ActionTrait;

    public string $path;

    public function __construct(
        public ReaderFilesystem $reader,
        public Filesystem $writer,
        public FileAttributes $file
    ) {
        $this->path = $this->file->path();
    }

    public function type(): string
    {
        return 'file';
    }

    public function isReaderExistingValid(): bool
    {
        return $this->isOnReader() === $this->reader->fileExists($this->path);
    }

    public function isWriterExistingValid(): bool
    {
        return $this->isOnWriter() === $this->writer->fileExists($this->path);
    }

    public function isExpected(): bool
    {
        return $this->writer->fileExists($this->path) && $this->getDifferences() === [];
    }
}
