<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

trait FileTrait
{
    use ActionTrait;

    public function __construct(
        public readonly ReaderFilesystem $reader,
        public readonly Filesystem $writer,
        public readonly FileAttributes $file
    ) {
        $this->path     = $file;
        $this->location = $file->path();
    }

    protected function readerExists(): bool
    {
        return $this->reader->fileExists($this->location);
    }

    protected function writerExists(): bool
    {
        return $this->writer->fileExists($this->location);
    }
}
