<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

/**
 * Helper class for the Sync class.
 * Gets filepaths that need writing, updating, and deleting.
 */
class Util
{
    /**
     * Paths to update (on TARGET and SOURCE but later timestamp on SOURCE).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $updates = [];

    /**
     * Paths to write (on SOURCE but NOT TARGET).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $writes = [];

    /**
     * Paths to delete (on TARGET but not SOURCE).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $deletes = [];

    public function __construct(Filesystem $source, Filesystem $target, string $dir = '/')
    {
        $source = $this->getPaths($source, $dir);
        $target  = $this->getPaths($target, $dir);

        // Get all file paths.
        $all = array_merge(
            array_keys($source),
            array_keys($target)
        );

        //  Find all WRITE, UPDATE, and DELETE paths.
        foreach ($all as $path) {
            $on_source = isset($source[$path]) === true;
            $on_target  = isset($target[$path]) === true;

            $path_source = $on_source ? $source[$path] : null;
            $path_target  = $on_target ? $target[$path] : null;

            // Update: On both and different properties
            if ($on_source === true && $on_target === true && static::isSame($path_source, $path_target) === false) {
                $this->updates[$path] = $path_source;
            }
            // Write: on Source, not Target
            elseif ($on_source === true && $on_target === false) {
                $this->writes[$path] = $path_source;
            }
            // Delete: not on Source, on Target
            elseif ($on_source === false && $on_target === true) {
                $this->deletes[$path] = $path_target;
            }
        }
    }

    /**
     * Should these paths be updated?
     */
    public static function isSame(StorageAttributes $one, StorageAttributes $two): bool
    {
        if ($one->path() !== $two->path() ||
            $one->isDir() !== $two->isDir() ||
            $one->isFile() !== $two->isFile() ||
            $one->type() !== $two->type() ||
            $one->lastModified() !== $two->lastModified() ||
            $one->visibility() !== $two->visibility()) {
            return false;
        }

        if ($one instanceof FileAttributes && $two instanceof FileAttributes && $one->isFile() && $two->isFile()) {
            return $one->fileSize() === $two->fileSize();
        }

        return true;
    }

    /**
     * Get paths to WRITE.
     *
     * @return string[]
     */
    public function getWrites(): array
    {
        return $this->writes;
    }

    /**
     * Get paths to DELETE.
     *
     * @return string[]
     */
    public function getDeletes(): array
    {
        return $this->deletes;
    }

    /**
     * Get paths to UPDATES.
     *
     * @return string[]
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }

    /**
     * Get paths on Filesystem.
     *
     * @return StorageAttributes[]
     */
    protected function getPaths(Filesystem $filesystem, string $dir): array
    {
        $paths = [];

        foreach ($filesystem->listContents($dir, true) as $content) {

            // Use filepath as key for comparison between SOURCE and TARGET.
            $paths[$content->path()] = $content;
        }

        // Sort by key (filepath).
        ksort($paths);

        return $paths;
    }
}
