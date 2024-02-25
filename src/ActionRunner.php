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
            function (FileAttributes $file): void {
                $this->writer->writeStream(
                    $file->path(),
                    $this->reader->readStream($file->path())
                );
            }
        );
    }

    public function syncUpdateFiles(): array
    {
        return $this->syncInternal(
            $this->actions->update_files,
            function (FileAttributes $file): void {
                $this->writer->writeStream(
                    $file->path(),
                    $this->reader->readStream($file->path())
                );
            }
        );
    }

    public function syncDeleteFiles(): array
    {
        return $this->syncInternal(
            $this->actions->delete_files,
            function (FileAttributes $file): void {
                $this->writer->delete($file->path());
            }
        );
    }

    public function syncCreateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->create_directories,
            function (DirectoryAttributes $directory): void {
                $this->writer->createDirectory($directory->path());
            }
        );
    }

    public function syncUpdateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->update_directories,
            function (DirectoryAttributes $directory): void {
                $this->writer->createDirectory($directory->path());
            }
        );
    }

    public function syncDeleteDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->delete_directories,
            function (DirectoryAttributes $directory): void {
                $this->writer->deleteDirectory($directory->path());
            }
        );
    }

    protected function syncInternal(array $batch, callable $action): array
    {
        return array_map(function (FileAttributes|DirectoryAttributes $current) use ($action) {
            $action($current);

            // Get created/updated/deleted object.
            $result = FilesystemHelper::loadPath($this->writer, $current->path());

            return [
                'type'       => $current->type(),
                'path'       => $current,
                'result'     => $result,
                'is_success' => FilesystemHelper::isSame($current, $result),
            ];
        }, $batch);
    }
}
