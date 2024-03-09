<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use SebastianBergmann\CodeCoverage\Report\Html\File;
use TCB\FlysystemSync\Helpers\Helper;
use TCB\FlysystemSync\Helpers\Loader;

readonly class Runner implements Contracts\Runner
{
    public Filesystem $reader;

    public Filesystem $writer;

    public function __construct(
        FilesystemAdapter $reader,
        FilesystemAdapter $writer
    ) {
        $this->reader = new Filesystem(new ReadOnl);
        $this->reader = new Filesystem($writer);
    }

    public function execute(?StorageAttributes $source, ?StorageAttributes $target): bool
    {
        $action = Action::get($source, $target);

        return match ($action) {
            Action::CREATE_FILE       => $this->createFile($source),
            Action::DELETE_FILE       => $this->deleteFile($target),
            Action::UPDATE_FILE       => $this->updateFile($source, $target),
            Action::NOTHING_FILE      => $this->nothingFile($source, $target),

            Action::CREATE_DIRECTORY  => $this->createDirectory($source),
            Action::DELETE_DIRECTORY  => $this->deleteDirectory($target),
            Action::UPDATE_DIRECTORY  => $this->updateDirectory($source, $target),
            Action::NOTHING_DIRECTORY => $this->nothingDirectory($source, $target),
        };
    }

    public function createFile(FileAttributes $source): bool
    {
        $this->writer->writeStream(
            $source->path(),
            $this->reader->readStream($source->path())
        );

        return $this->isSuccessFile($source->path(), $source->path());
    }

    public function deleteFile(FileAttributes $target): bool
    {
        $this->writer->delete($target->path());

        return $this->isSuccessFileDeleted($target->path(), $target->path());
    }

    public function updateFile(FileAttributes $source, StorageAttributes $target): bool
    {
        $this->writer->writeStream(
            $target->path(),
            $this->reader->readStream($source->path())
        );

        return $this->isSuccessFile($source->path(), $target->path());
    }

    public function nothingFile(FileAttributes $source, FileAttributes $target): bool
    {
        return $this->isSuccessFile($source->path(), $target->path());
    }

    public function createDirectory(DirectoryAttributes $source): bool
    {
        $this->writer->createDirectory($source->path());

        return $this->isSuccessDirectory($source->path(), $source->path());
    }

    public function deleteDirectory(DirectoryAttributes $target): bool
    {
        $this->writer->deleteDirectory($target->path());

        return $this->isSuccessDirectoryDeleted($target->path(), $target->path());
    }

    public function updateDirectory(DirectoryAttributes $source, StorageAttributes $target): bool
    {
        // Directories don't update.
        // Only difference would be "visibility" which we're ignoring right now.
        return $this->isSuccessDirectory($source->path(), $target->path());
    }

    public function nothingDirectory(DirectoryAttributes $source, DirectoryAttributes $target): bool
    {
        return $this->isSuccessDirectory($source->path(), $target->path());
    }

    protected function isSuccessFile(string $path_source, string $path_target): bool
    {
        $source = Loader::getFile($this->reader, $path_source);
        $target = Loader::getFile($this->writer, $path_target);

        if ($source === null || $target === null) {
            return false;
        }

        return Helper::isSame($source, $target);
    }

    protected function isSuccessFileDeleted(string $path_source, string $path_target): bool
    {
        $source = Loader::getFile($this->reader, $path_source);
        $target = Loader::getFile($this->writer, $path_target);

        return $source === null && $target === null;
    }

    protected function isSuccessDirectory(string $path_source, string $path_target): bool
    {
        $source = Loader::getDirectory($this->reader, $path_source);
        $target = Loader::getDirectory($this->writer, $path_target);

        if ($source === null || $target === null) {
            return false;
        }

        return Helper::isSame($source, $target);
    }

    protected function isSuccessDirectoryDeleted(string $path_source, string $path_target): bool
    {
        $source = Loader::getDirectory($this->reader, $path_source);
        $target = Loader::getDirectory($this->writer, $path_target);

        return $source === null && $target === null;
    }
}
