<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Directory\CreateDirectory;
use TCB\FlysystemSync\Action\Directory\DeleteDirectory;
use TCB\FlysystemSync\Action\Directory\NothingDirectory;
use TCB\FlysystemSync\Action\Directory\UpdateDirectory;
use TCB\FlysystemSync\Action\File\CreateFile;
use TCB\FlysystemSync\Action\File\DeleteFile;
use TCB\FlysystemSync\Action\File\NothingFile;
use TCB\FlysystemSync\Action\File\UpdateFile;
use TCB\FlysystemSync\Filesystem;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

use function array_diff_key;
use function array_filter;
use function array_intersect_key;
use function array_map;
use function gettype;
use function sprintf;

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
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $sources
     * @param FileAttributes[]|DirectoryAttributes[]|null[] $targets
     * @throws \Exception
     */
    public function __construct(
        LeagueFilesystem|FilesystemAdapter $reader,
        LeagueFilesystem|FilesystemAdapter $writer,
        array $sources,
        array $targets
    ) {
        $reader = new ReaderFilesystem($reader);
        $writer = HelperFilesystem::prepareFilesystem($writer);

        $sources = $this->assertInputs($sources);
        $targets = $this->assertInputs($targets);

        $creates = array_diff_key($sources, $targets);          // Has sources, no targets
        $deletes = array_diff_key($targets, $sources);          // Has targets, no sources

        // Has both targets and sources.
        // Sort through files/directories with and without differences.
        $updates = $this->separateDifferents($sources, $targets);

        // Creates
        $this->create_files = array_map(
            fn (FileAttributes $file) => new CreateFile($reader, $writer, $file),
            $this->onlyFiles($creates)
        );

        $this->create_directories = array_map(
            fn (DirectoryAttributes $directory) => new CreateDirectory($reader, $writer, $directory),
            $this->onlyDirectories($creates)
        );

        // Deletes
        $this->delete_files = array_map(
            fn (FileAttributes $file) => new DeleteFile($reader, $writer, $file),
            $this->onlyFiles($deletes)
        );

        $this->delete_directories = array_map(
            fn (DirectoryAttributes $directory) => new DeleteDirectory($reader, $writer, $directory),
            $this->onlyDirectories($deletes)
        );

        // Updates
        $this->update_files = array_map(
            fn (FileAttributes $file) => new UpdateFile($reader, $writer, $file),
            $this->onlyFiles($updates['diffs'])
        );

        $this->update_directories = array_map(
            fn (DirectoryAttributes $directory) => new UpdateDirectory($reader, $writer, $directory),
            $this->onlyDirectories($updates['diffs'])
        );

        // "Nothings" - no action needed
        // This can be useful for logging.
        $this->nothing_files = array_map(
            fn (FileAttributes $file) => new NothingFile($reader, $writer, $file),
            $this->onlyFiles($updates['sames'])
        );

        $this->nothing_directories = array_map(
            fn (DirectoryAttributes $directory) => new NothingDirectory($reader, $writer, $directory),
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

    protected function assertInputs(array $array): array
    {
        // Remove NULLs
        $array = array_filter($array, fn (mixed $value): bool => $value !== null);

        // Cannot use array_filter(), need to get the $path key.
        foreach ($array as $path => $value) {
            // Must be either FileAttributes or DirectoryAttributes
            if ($value instanceof FileAttributes === false &&
                $value instanceof DirectoryAttributes === false
            ) {
                throw new \Exception(sprintf(
                    'Invalid path "%s", must be object %s or %s, found: %s',
                    $path,
                    FileAttributes::class,
                    DirectoryAttributes::class,
                    gettype($value)
                ));
            }

            // Paths have to equal.

            // Could be a numeric file/directory path.
            // Cast $path
            if ((string) $path !== $value->path()) {
                throw new \Exception(
                    sprintf('Paths do not match for key/value pair, "%s" key, "%s" value', $path, $value->path())
                );
            }
        }

        return $array;
    }

    /**
     * Get updates from SOURCES and TARGETS.
     * Then separate them into "sames" (same files/dirs) and "diffs" (files/dirs have differences).
     *
     * @throws \Exception
     */
    protected function separateDifferents(array $sources, array $targets): array
    {
        $sames = [];
        $diffs = [];

        $updates = array_intersect_key($sources, $targets);

        foreach ($updates as $source) {
            // Must use array_key_exists, value could be NULL and isset() or ??  null wouldn't work.
            $target = $targets[$source->path()] ?? throw new \Exception;

            Filesystem\HelperFilesystem::isSame($source, $target) ?
                $sames[$source->path()] = $source :
                $diffs[$source->path()] = $source;
        }

        return [
            'sames' => $sames,
            'diffs' => $diffs,
        ];
    }

    protected function onlyFiles(array $contents): array
    {
        return array_filter(
            $contents,
            fn (StorageAttributes $current) => $current instanceof FileAttributes
        );
    }

    protected function onlyDirectories(array $contents): array
    {
        return array_filter(
            $contents,
            fn (StorageAttributes $current) => $current instanceof DirectoryAttributes
        );
    }
}
