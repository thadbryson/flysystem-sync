<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Actions\Directory\Create as CreateDirectory;
use TCB\FlysystemSync\Actions\Directory\Delete as DeleteDirectory;
use TCB\FlysystemSync\Actions\Directory\Update as UpdateDirectory;
use TCB\FlysystemSync\Actions\File\Create as CreateFile;
use TCB\FlysystemSync\Actions\File\Delete as DeleteFile;
use TCB\FlysystemSync\Actions\File\Update as UpdateFile;
use TCB\FlysystemSync\Filesystems\Reader;

readonly class Factory
{
    protected Reader $reader;

    protected FilesystemOperator $writer;

    public function __construct(Reader $reader, FilesystemOperator $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function create(StorageAttributes $source, string $target): CreateDirectory|CreateFile
    {
        return $source->isDir() ?
            new Directory\Create($this->reader, $this->writer, $source->path(), $target) :
            new File\Create($this->reader, $this->writer, $source->path(), $target);
    }

    public function update(StorageAttributes $source, StorageAttributes $target): UpdateDirectory|UpdateFile
    {
        return $source->isDir() ?
            new Directory\Update($this->reader, $this->writer, $source->path(), $target->path()) :
            new File\Update($this->reader, $this->writer, $source->path(), $target->path());
    }

    public function delete(StorageAttributes $target): DeleteDirectory|DeleteFile
    {
        return $target->isDir() ?
            new Directory\Delete($this->reader, $this->writer, null, $target->path()) :
            new File\Delete($this->reader, $this->writer, null, $target->path());
    }
}
