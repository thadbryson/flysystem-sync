<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Helper\ActionHelper;

readonly class ActionRunner
{
    public readonly ActionHelper $actions;

    public function __construct(
        protected Filesystem $reader,
        protected Filesystem $writer,
        array $sources,
        array $targets
    ) {
        // Run actions
        $this->actions = new ActionHelper($sources, $targets);
    }

    public function syncAll(): array
    {
        return [
            'create_files'       => $this->syncCreateFiles(),
            'create_directories' => $this->syncCreateDirectories(),
            'update_files'       => $this->syncUpdateFiles(),
            'update_directories' => $this->syncUpdateDirectories(),
            'delete_files'       => $this->syncDeleteFiles(),
            'delete_directories' => $this->syncDeleteDirectories(),
        ];
    }

    public function syncCreateFiles(): array
    {
        // Creates
        return $this->syncInternal(
            $this->actions->create_files,
            fn (FileAttributes $file) => $this->createFile($file)
        );
    }

    public function syncCreateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->create_directories,
            fn (DirectoryAttributes $directory) => $this->createDirectory($directory)
        );
    }

    public function syncUpdateFiles(): array
    {
        return $this->syncInternal(
            $this->actions->update_files,
            fn (FileAttributes $file) => $this->updateFile($file)
        );
    }

    public function syncUpdateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->update_directories,
            fn (DirectoryAttributes $directory) => $this->updateDirectory($directory)
        );
    }

    public function syncDeleteFiles(): array
    {
        return $this->syncInternal(
            $this->actions->delete_files,
            fn (FileAttributes $file) => $this->deleteFile($file)
        );
    }

    public function syncDeleteDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->delete_directories,
            fn (DirectoryAttributes $directory) => $this->deleteDirectory($directory)
        );
    }

    protected function syncInternal(array $current, callable $action): array
    {
        $result = [];

        /** @var FileAttributes|DirectoryAttributes $path */
        foreach ($current as $path) {
            $action($path);
            $result[$path->path()] = $path;
        }

        return $result;
    }

    protected function createFile(FileAttributes $file): void
    {
        $this->writer->writeStream(
            $file->path(),
            $this->reader->readStream($file->path())
        );
    }

    protected function createDirectory(DirectoryAttributes $directory): void
    {
        $this->writer->createDirectory($directory->path());
    }

    protected function updateFile(FileAttributes $file): void
    {
        $this->createFile($file);
    }

    protected function updateDirectory(DirectoryAttributes $directory): void
    {
        $this->createDirectory($directory);
    }

    protected function deleteFile(FileAttributes $file): void
    {
        $this->writer->delete($file->path());
    }

    protected function deleteDirectory(DirectoryAttributes $directory): void
    {
        $this->writer->deleteDirectory($directory->path());
    }
}
