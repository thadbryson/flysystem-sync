<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Action\Directory\CreateDirectoryAction;
use TCB\FlysystemSync\Action\Directory\DeleteDirectoryAction;
use TCB\FlysystemSync\Action\Directory\NothingDirectoryAction;
use TCB\FlysystemSync\Action\Directory\UpdateDirectoryAction;
use TCB\FlysystemSync\Action\File\CreateFileAction;
use TCB\FlysystemSync\Action\File\DeleteFileAction;
use TCB\FlysystemSync\Action\File\NothingFileAction;
use TCB\FlysystemSync\Action\File\UpdateFileAction;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function array_diff_key;
use function array_filter;
use function array_intersect_key;
use function array_map;

/**
 * Contains Action objects for each type (File, Directory) and
 * Action (create, update, delete, nothing).
 */
class Bag
{
    // These properties can't be "readonly".
    // They're manipulated on the Runner.

    // Creates
    public array $create_files;

    public array $create_directories;

    // Deletes
    public array $delete_files;

    public array $delete_directories;

    // Updates
    public array $update_files;

    public array $update_directories;

    // Nothing happens with these
    public array $nothing_files;

    public array $nothing_directories;

    /**
     * @param File[]|Directory[] $sources
     * @param File[]|Directory[] $targets
     */
    public function __construct(
        array $sources,
        array $targets
    ) {
        $sources = array_filter($sources, fn (mixed $value): bool => $value !== null);
        $targets = array_filter($targets, fn (mixed $value): bool => $value !== null);

        $creates = array_diff_key($sources, $targets);  // Has sources, no targets
        $deletes = array_diff_key($targets, $sources);  // Has targets, no sources

        // Has both targets and sources.
        // Sort through files/directories with and without differences.
        $updates = $this->separateDifferents($sources, $targets);

        // Creates
        $this->create_files = array_map(
            fn (File $file) => new CreateFileAction($file),
            $this->onlyFiles($creates)
        );

        $this->create_directories = array_map(
            fn (Directory $directory) => new CreateDirectoryAction($directory),
            $this->onlyDirectories($creates)
        );

        // Deletes
        $this->delete_files = array_map(
            fn (File $file) => new DeleteFileAction($file),
            $this->onlyFiles($deletes)
        );

        $this->delete_directories = array_map(
            fn (Directory $directory) => new DeleteDirectoryAction($directory),
            $this->onlyDirectories($deletes)
        );

        // Updates
        $this->update_files = array_map(
            fn (File $file) => new UpdateFileAction($file),
            $this->onlyFiles($updates['diffs'])
        );

        $this->update_directories = array_map(
            fn (Directory $directory) => new UpdateDirectoryAction($directory),
            $this->onlyDirectories($updates['diffs'])
        );

        // "Nothings" - no action needed
        // This can be useful for logging.
        $this->nothing_files = array_map(
            fn (File $file) => new NothingFileAction($file),
            $this->onlyFiles($updates['sames'])
        );

        $this->nothing_directories = array_map(
            fn (Directory $directory) => new NothingDirectoryAction($directory),
            $this->onlyDirectories($updates['sames'])
        );
    }

    /**
     * Map each array with callable $callable
     * ->create_files
     * ->create_directories
     * etc
     */
    public function map(callable $callable): static
    {
        $this->create_files       = array_map($callable, $this->create_files);
        $this->create_directories = array_map($callable, $this->create_directories);

        $this->delete_files       = array_map($callable, $this->delete_files);
        $this->delete_directories = array_map($callable, $this->delete_directories);

        $this->update_files       = array_map($callable, $this->update_files);
        $this->update_directories = array_map($callable, $this->update_directories);

        $this->nothing_files       = array_map($callable, $this->nothing_files);
        $this->nothing_directories = array_map($callable, $this->nothing_directories);

        return $this;
    }

    /**
     * Get updates from SOURCES and TARGETS.
     * Then separate them into "sames" (same files/dirs) and "diffs" (files/dirs have differences).
     *
     * @throws \Exception
     */
    private function separateDifferents(array $sources, array $targets): array
    {
        $sames = [];
        $diffs = [];

        $updates = array_intersect_key($sources, $targets);

        foreach ($updates as $source) {
            // Must use array_key_exists, value could be NULL and isset() or ??  null wouldn't work.
            $target = $targets[$source->path()] ?? throw new \Exception;

            HelperFilesystem::isSame($source, $target) ?
                $sames[$source->path()] = $source :
                $diffs[$source->path()] = $source;
        }

        return [
            'sames' => $sames,
            'diffs' => $diffs,
        ];
    }

    private function onlyFiles(array $contents): array
    {
        return array_filter(
            $contents,
            fn (File|Directory $current) => $current instanceof File
        );
    }

    private function onlyDirectories(array $contents): array
    {
        return array_filter(
            $contents,
            fn (File|Directory $current) => $current instanceof Directory
        );
    }
}
