<?php

declare(strict_types=1);

namespace TCB\FlysystemSync\Directory;

use Illuminate\Support\Collection;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Action;
use TCB\FlysystemSync\Action\Type;

/**
 * Helper class for the Sync class.
 * Gets filepaths that need writing, updating, and deleting.
 *
 * CREATES - Paths to CREATE (on SOURCE but NOT TARGET).
 * DELETES - (on TARGET but not SOURCE).
 * UPDATES - (on TARGET and SOURCE but later timestamp on SOURCE).
 * NOTHING - with (on TARGET and SOURCE but no changes).
 */
readonly class Actions
{
    public Collection $creates_files;
    public Collection $deletes_files;
    public Collection $updates_files;
    public Collection $nothing_files;

    public Collection $creates_directories;
    public Collection $deletes_directories;
    public Collection $updates_directories;
    public Collection $nothing_directories;

    /**
     * Get Contents of Filesystem SOURCE, TARGET with actions needed.
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public function __construct(
        Filesystem $sources,
        Filesystem $targets,
        public string $directory
    )
    {
        // @todo handle different TARGET paths
        $sources = $this->getPaths($sources, $directory);
        $targets = $this->getPaths($targets, $directory);

        $all = Collection::make()
            ->merge($sources->keys())
            ->merge($targets->keys())
            ->map(fn (string $path): Action => new Action(
                $sources->get($path),
                $targets->get($path)
            ));

        $this->creates_files = $this->organize($all, Type::CREATED, true);
        $this->deletes_files = $this->organize($all, Type::DELETED, true);
        $this->updates_files = $this->organize($all, Type::UPDATED, true);
        $this->nothing_files = $this->organize($all, Type::NOTHING, true);

        $this->creates_directories = $this->organize($all, Type::CREATED, false);
        $this->deletes_directories = $this->organize($all, Type::DELETED, false);
        $this->updates_directories = $this->organize($all, Type::UPDATED, false);
        $this->nothing_directories = $this->organize($all, Type::NOTHING, false);
    }

    /**
     *  Get paths on Filesystem in Collection object, keyed by "path".
     *
     * @throws \League\Flysystem\FilesystemException
     */
    protected function getPaths(Filesystem $filesystem, string $directory): Collection
    {
        // Get all the directory contents.
        // Must be "DEEP", not just 1st level.
        // All sub-directories and contents.
        $filesystem = $filesystem->listContents($directory, FilesystemReader::LIST_DEEP);

        return Collection::make($filesystem)
            ->keyBy(
                fn (StorageAttributes $attributes): string => $attributes->path()
            );
    }

    /**
     * Organize all files/directories by Action\Type and file/directory. Sort by path keys.
     */
    protected function organize(Collection $all, Type $type, bool $file): Collection
    {
        return $all
            ->where('type', $type)
            ->where('file', $file)
            ->sortKeys();
    }
}
