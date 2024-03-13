<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use TCB\FlysystemSync\Helpers\Helper;
use TCB\FlysystemSync\Helpers\Loader;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

readonly class Runner implements Contracts\Runner
{
    public Filesystem $reader;

    public Filesystem $writer;

    public function __construct(FilesystemAdapter $reader, FilesystemAdapter $writer)
    {
        $this->reader = new Filesystem(new ReadOnlyFilesystemAdapter($reader));
        $this->writer = new Filesystem($writer);
    }

    public function execute(?Path $source, ?Path $target): array
    {
        $action = Action::get($source, $target);

        match ($action) {
            Action::CREATE_FILE       => $this->createFile($source),
            Action::DELETE_FILE       => $this->deleteFile($target),
            Action::UPDATE_FILE       => $this->updateFile($source, $target),
            Action::NOTHING_FILE      => $this->nothingFile($source, $target),

            Action::CREATE_DIRECTORY  => $this->createDirectory($source),
            Action::DELETE_DIRECTORY  => $this->deleteDirectory($target),
            Action::UPDATE_DIRECTORY  => $this->updateDirectory($source, $target),
            Action::NOTHING_DIRECTORY => $this->nothingDirectory($source, $target),
        };

        $result = match ($action) {
            Action::CREATE_FILE       => $this->sameFiles($source->path),
            Action::DELETE_FILE       => $this->fileExistsNot($target->path),
            Action::UPDATE_FILE       => $this->sameFiles($source->path),
            Action::NOTHING_FILE      => $this->sameFiles($source->path),

            Action::CREATE_DIRECTORY  => $this->sameDirectories($source->path),
            Action::DELETE_DIRECTORY  => $this->directoryExistsNot($target->path),
            Action::UPDATE_DIRECTORY  => $this->sameDirectories($source->path),
            Action::NOTHING_DIRECTORY => $this->sameDirectories($source->path),
        };

        return [
            'source'        => $source,
            'target'        => $target,
            'action'        => $action,
            'execute_after' => $result,
        ];
    }

    public function createFile(File $source): void
    {
        $this->writer->writeStream(
            $source->path,
            $this->reader->readStream($source->path)
        );
    }

    public function deleteFile(File $target): void
    {
        $this->writer->delete($target->path);
    }

    public function updateFile(File $source, Path $target): void
    {
        $this->writer->writeStream(
            $target->path,
            $this->reader->readStream($source->path)
        );
    }

    public function nothingFile(File $source, File $target): void
    {
    }

    public function createDirectory(Directory $source): void
    {
        $this->writer->createDirectory($source->path);
    }

    public function deleteDirectory(Directory $target): void
    {
        $this->writer->deleteDirectory($target->path);
    }

    public function updateDirectory(Directory $source, Path $target): void
    {
        // Only thing for directories would be if the TARGET is not a directory.
        // They'd have the same name and other properties.
        $this->writer->createDirectory($target->path);
    }

    public function nothingDirectory(Directory $source, Directory $target): void
    {
    }

    public function sameFiles(string $path): bool
    {
        $source = Loader::getFile($this->reader, $path);
        $target = Loader::getFile($this->writer, $path);

        return Helper::isSame($source, $target);
    }

    public function fileExistsNot(string $path): bool
    {
        return $this->reader->fileExists($path) === false &&
               $this->writer->fileExists($path) === false;
    }

    public function sameDirectories(string $path): bool
    {
        $source = Loader::getDirectory($this->reader, $path);
        $target = Loader::getDirectory($this->writer, $path);

        return Helper::isSame($source, $target);
    }

    public function directoryExistsNot(string $path): bool
    {
        return $this->reader->directoryExists($path) === false &&
               $this->writer->directoryExists($path) === false;
    }
}
