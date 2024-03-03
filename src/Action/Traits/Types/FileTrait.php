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

    protected function readerExists(): bool
    {
        return  $this->reader->fileExists($this->path);
    }

    protected function writerExists(): bool
    {
        return $this->writer->fileExists($this->path);
    }
}
