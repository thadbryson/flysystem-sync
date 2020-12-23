<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

class Util
{
    /**
     * Paths to update (on SLAVE and MASTER but later timestamp on MASTER).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $updates = [];

    /**
     * Paths to write (on MASTER but NOT SLAVE).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $writes = [];

    /**
     * Paths to delete (on SLAVE but not MASTER).
     *
     * @var iterable|StorageAttributes[]
     */
    protected $deletes = [];

    public function __construct(Filesystem $master, Filesystem $slave, string $dir = '/')
    {
        $master = $this->getPaths($master, $dir);
        $slave  = $this->getPaths($slave, $dir);

        // Get all file paths.
        $all = array_merge(
            array_keys($master),
            array_keys($slave)
        );

        //  Find all WRITE, UPDATE, and DELETE paths.
        foreach ($all as $path) {
            $on_master = isset($master[$path]) === true;
            $on_slave  = isset($slave[$path]) === true;

            $path_master = $on_master ? $master[$path] : null;
            $path_slave  = $on_slave ? $slave[$path] : null;

            // Update: On both and different properties
            if ($on_master === true && $on_slave === true && static::isSame($path_master, $path_slave) === false) {
                $this->updates[$path] = $path_master;
            }
            // Write: on Master, not Slave
            elseif ($on_master === true && $on_slave === false) {
                $this->writes[$path] = $path_master;
            }
            // Delete: not on Master, on Slave
            elseif ($on_master === false && $on_slave === true) {
                $this->deletes[$path] = $path_slave;
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
     */
    public function getWrites(): array
    {
        return $this->writes;
    }

    /**
     * Get paths to DELETE.
     */
    public function getDeletes(): array
    {
        return $this->deletes;
    }

    /**
     * Get paths to UPDATES.
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

            // Use filepath as key for comparison between MASTER and SLAVE.
            $paths[$content->path()] = $content;
        }

        // Sort by key (filepath).
        ksort($paths);

        return $paths;
    }
}
