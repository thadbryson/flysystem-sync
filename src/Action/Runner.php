<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Actions\Contracts\Action;
use TCB\FlysystemSync\Action\Actions\Directory;
use TCB\FlysystemSync\Action\Actions\File;

use function array_map;

readonly class Runner
{
    public array $create_files;

    public array $update_files;

    public array $delete_files;

    public array $nothing_files;

    public array $create_directories;

    public array $update_directories;

    public array $delete_directories;

    public array $nothing_directories;

    public function __construct(
        FilesystemOperator|FilesystemReader $reader,
        FilesystemOperator $writer,
        array $sources,
        array $targets
    ) {
        // Run actions
        $sorter = new Sorter($sources, $targets);

        $this->nothing_files       = $sorter->nothing_files;
        $this->nothing_directories = $sorter->nothing_directories;

        // File actions
        $this->create_files = array_map(
            fn (StorageAttributes $file) => new File\Create($reader, $writer, $file),
            $sorter->create_files
        );

        $this->update_files = array_map(
            fn (StorageAttributes $file) => new File\Update($reader, $writer, $file),
            $sorter->update_files
        );

        $this->delete_files = array_map(
            fn (StorageAttributes $file) => new File\Delete($reader, $writer, $file),
            $sorter->delete_files
        );

        // Directory actions
        $this->create_directories = array_map(
            fn (StorageAttributes $directory) => new Directory\Create($reader, $writer, $directory),
            $sorter->create_directories
        );

        $this->update_directories = array_map(
            fn (StorageAttributes $directory) => new Directory\Update($reader, $writer, $directory),
            $sorter->update_directories
        );

        $this->delete_directories = array_map(
            fn (StorageAttributes $directory) => new Directory\Delete($reader, $writer, $directory),
            $sorter->delete_directories
        );
    }

    public function syncAll(): array
    {
        return [
            'create_files'  => $this->syncCreateFiles(),
            'update_files'  => $this->syncUpdateFiles(),
            'delete_files'  => $this->syncDeleteFiles(),
            'nothing_files' => $this->nothing_files,

            'create_directories'  => $this->syncCreateDirectories(),
            'update_directories'  => $this->syncUpdateDirectories(),
            'delete_directories'  => $this->syncDeleteDirectories(),
            'nothing_directories' => $this->nothing_directories,
        ];
    }

    public function syncCreateFiles(): array
    {
        // Creates
        return $this->syncInternal(
            $this->create_files,
        );
    }

    public function syncUpdateFiles(): array
    {
        return $this->syncInternal(
            $this->update_files,
        );
    }

    public function syncDeleteFiles(): array
    {
        return $this->syncInternal(
            $this->delete_files,
        );
    }

    public function syncCreateDirectories(): array
    {
        return $this->syncInternal(
            $this->create_directories,
        );
    }

    public function syncUpdateDirectories(): array
    {
        return $this->syncInternal(
            $this->update_directories,
        );
    }

    public function syncDeleteDirectories(): array
    {
        return $this->syncInternal(
            $this->delete_directories,
        );
    }

    protected function syncInternal(array $batch): array
    {
        return array_map(
            function (Action $action): Result {
                $has_ran = false;

                if ($action->isReady() === true) {
                    // Call action
                    $action->execute();
                    $has_ran = true;
                }

                return new Result($action, $has_ran);
            },

            $batch
        );
    }
}
