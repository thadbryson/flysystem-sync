<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Helper\ActionHelper;
use TCB\FlysystemSync\Helper\FilesystemHelper;

use function array_map;

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
            'create',
            fn (FileAttributes $file) => $this->createFile($file)
        );
    }

    public function syncCreateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->create_directories,
            'create',
            fn (DirectoryAttributes $directory) => $this->createDirectory($directory)
        );
    }

    public function syncUpdateFiles(): array
    {
        return $this->syncInternal(
            $this->actions->update_files,
            'update',
            fn (FileAttributes $file) => $this->updateFile($file)
        );
    }

    public function syncUpdateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->update_directories,
            'update',
            fn (DirectoryAttributes $directory) => $this->updateDirectory($directory)
        );
    }

    public function syncDeleteFiles(): array
    {
        return $this->syncInternal(
            $this->actions->delete_files,
            'delete',
            fn (FileAttributes $file) => $this->deleteFile($file)
        );
    }

    public function syncDeleteDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->delete_directories,
            'delete',
            fn (DirectoryAttributes $directory) => $this->deleteDirectory($directory)
        );
    }

    protected function syncInternal(array $batch, string $description, callable $action): array
    {
        return array_map(function (FileAttributes|DirectoryAttributes $current) use ($description, $action) {
            $result = $action($current);

            return [
                'action'  => $description,
                'type'    => $current->type(),
                'path'    => $current,
                'result'  => $result,
                'is_same' => FilesystemHelper::isSame($current, $result),
            ];
        }, $batch);
    }

    protected function createFile(FileAttributes $file): FileAttributes|DirectoryAttributes|null
    {
        $this->writer->writeStream(
            $file->path(),
            $this->reader->readStream($file->path())
        );

        return FilesystemHelper::loadPath($this->writer, $file->path());
    }

    protected function createDirectory(DirectoryAttributes $directory): FileAttributes|DirectoryAttributes|null
    {
        $this->writer->createDirectory($directory->path());

        return FilesystemHelper::loadPath($this->writer, $directory->path());
    }

    protected function updateFile(FileAttributes $file): FileAttributes|DirectoryAttributes|null
    {
        $this->createFile($file);

        return FilesystemHelper::loadPath($this->writer, $file->path());
    }

    protected function updateDirectory(DirectoryAttributes $directory): FileAttributes|DirectoryAttributes|null
    {
        $this->createDirectory($directory);

        return FilesystemHelper::loadPath($this->writer, $directory->path());
    }

    protected function deleteFile(FileAttributes $file): FileAttributes|DirectoryAttributes|null
    {
        $this->writer->delete($file->path());

        return FilesystemHelper::loadPath($this->writer, $file->path());
    }

    protected function deleteDirectory(DirectoryAttributes $directory): FileAttributes|DirectoryAttributes|null
    {
        $this->writer->deleteDirectory($directory->path());

        return FilesystemHelper::loadPath($this->writer, $directory->path());
    }
}
