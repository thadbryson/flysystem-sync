<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Actions\Action as ActionObject;

class Factory
{
    protected ?Filesystem $reader = null;

    protected ?Filesystem $writer= null;

    public function setFilesystems(Filesystem $reader, Filesystem $writer): void
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function make(string $bag, ?StorageAttributes $source, ?StorageAttributes $target): Action
    {
        if ($this->reader === null || $this->writer === null) {
            throw new \Exception('');
        }

        return match ($bag) {
            'create_files'        => $this->createFile($source, $target),
            'delete_files'        => $this->deleteFile($source, $target),
            'update_files'        => $this->updateFile($source, $target),
            'nothing_files'       => $this->nothingFile($source, $target),
            'create_directories'  => $this->createDirectory($source, $target),
            'delete_directories'  => $this->deleteDirectory($source, $target),
            'update_directories'  => $this->updateDirectory($source, $target),
            'nothing_directories' => $this->nothingDirectory($source, $target),
        };
    }

    protected function createFile(FileAttributes $source, null $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function deleteFile(null $source, FileAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function updateFile(FileAttributes $source, StorageAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function nothingFile(FileAttributes $source, FileAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function createDirectory(DirectoryAttributes $source, null $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function deleteDirectory(null $source, DirectoryAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function updateDirectory(DirectoryAttributes $source, StorageAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }

    protected function nothingDirectory(DirectoryAttributes $source, DirectoryAttributes $target): Action
    {
        return new ActionObject($this->reader, $this->writer, $source, $target);
    }
}
