<?php

declare(strict_types=1);

namespace TCB\FlysystemSync;

use Illuminate\Support\Collection;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;

/**
 * Helper class for the Sync class.
 * Gets filepaths that need writing, updating, and deleting.
 */
readonly class ContentBag
{
    protected const string ATTRIBUTE_ON_SOURCE = 'on_Source';

    /**
     * Paths to CREATE (on SOURCE but NOT TARGET).
     */
    public readonly Collection $creates;

    /**
     * Paths to DELETE (on TARGET but not SOURCE).
     */
    public readonly Collection $deletes;

    /**
     * Paths to UPDATE (on TARGET and SOURCE but later timestamp on SOURCE).
     */
    public readonly Collection $updates;

    /**
     * Paths to do NOTHING with (on TARGET and SOURCE but no changes).
     */
    public readonly Collection $nothing;

    /**
     * Get Contents of Filesystem SOURCE, TARGET with actions needed.
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public function __construct(Filesystem $sources, Filesystem $targets, string $directory = '/')
    {
        $sources = $this->getPaths($sources, $directory);
        $targets = $this->getPaths($targets, $directory);

        // Merge keys from both.
        $all = $sources->keys()
            ->merge($targets->keys());

        // Get all file paths.
        // Makes new Collection object.
        // Find all UPDATE, CREATE, and DELETE path.
        $all = $all
            ->groupBy(function (string $path) use ($sources, $targets): array {

                $source = $sources->get($path);
                $target = $sources->get($path);

                return [
                    'path'   => $path,
                    'action' => ActionEnum::get($source, $target),
                    'source' => $source,
                ];
            });

        $this->creates = $all->get(ActionEnum::CREATE);
        $this->deletes = $all->get(ActionEnum::DELETE);
        $this->updates = $all->get(ActionEnum::UPDATE);
        $this->nothing = $all->get(ActionEnum::NOTHING);
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
}
