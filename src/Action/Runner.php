<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem as BaseFilesystem;
use TCB\FlysystemSync\Filesystem;

use function array_map;

readonly class Runner
{
    public readonly Sorter $actions;

    public function __construct(
        protected BaseFilesystem $reader,
        protected BaseFilesystem $writer,
        array $sources,
        array $targets
    ) {
        // Run actions
        $this->actions = new Sorter($sources, $targets);
    }

    public function syncAll(): array
    {
        return [
            'create_files' => $this->syncCreateFiles(),
            'update_files' => $this->syncUpdateFiles(),
            'delete_files' => $this->syncDeleteFiles(),

            'create_directories' => $this->syncCreateDirectories(),
            'update_directories' => $this->syncUpdateDirectories(),
            'delete_directories' => $this->syncDeleteDirectories(),
        ];
    }

    public function syncCreateFiles(): array
    {
        // Creates
        return $this->syncInternal(
            $this->actions->create_files,

            function (FileAttributes $file): bool {
                $this->writer->writeStream(
                    $file->path(),
                    $this->reader->readStream($file->path())
                );

                return true;
            }
        );
    }

    public function syncUpdateFiles(): array
    {
        return $this->syncInternal(
            $this->actions->update_files,

            function (FileAttributes $file): bool {
                $this->writer->writeStream(
                    $file->path(),
                    $this->reader->readStream($file->path())
                );

                return true;
            }
        );
    }

    public function syncDeleteFiles(): array
    {
        return $this->syncInternal(
            $this->actions->delete_files,

            function (FileAttributes $file): bool {
                $this->writer->delete($file->path());

                return false;
            }
        );
    }

    public function syncCreateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->create_directories,

            function (DirectoryAttributes $directory): bool {
                $this->writer->createDirectory($directory->path());

                return true;
            }
        );
    }

    public function syncUpdateDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->update_directories,

            function (DirectoryAttributes $directory): bool {
                $this->writer->createDirectory($directory->path());

                return true;
            }
        );
    }

    public function syncDeleteDirectories(): array
    {
        return $this->syncInternal(
            $this->actions->delete_directories,

            function (DirectoryAttributes $directory): bool {
                $this->writer->deleteDirectory($directory->path());

                return false;
            }
        );
    }

    protected function syncInternal(array $batch, callable $action): array
    {
        return array_map(
            function (FileAttributes|DirectoryAttributes $current) use ($action): Result {
                // Call action
                $should_exist = $action($current);

                // Get created/updated/deleted object.
                $result = Filesystem\Helper::loadPath($this->writer, $current->path());

                $exists = $current instanceof FileAttributes ?
                    $this->writer->fileExists($current->path()) :
                    $this->writer->directoryExists($current->path());

                return new Result($current, $result, $should_exist, $exists);
            },
            $batch
        );
    }
}
