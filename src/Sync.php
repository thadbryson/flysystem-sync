<?php

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;

/**
 * class Sync
 *
 * @author Thad Bryson <thadbry@gmail.com>
 */
class Sync
{
    /**
     * Master filesystem.
     *
     * @var FilesystemInterface
     */
    protected $master;

    /**
     * @var FilesystemInterface
     */
    protected $slave;

    /**
     * Slave filesystem.
     *
     * Root directory to sync.
     *
     * @var string
     */
    protected $dir;


    /**
     * Sync constructor.
     *
     * @param FilesystemInterface $master
     * @param FilesystemInterface $slave
     * @param string $dir
     */
    public function __construct(FilesystemInterface $master, FilesystemInterface $slave, $dir = '/')
    {
        $this->master = $master;
        $this->slave  = $slave;

        $this->dir = $dir;
    }

    /**
     * Get paths on Filesystem.
     *
     * @param FilesystemInterface $filesystem
     * @param $skipDirs
     * @return array
     */
    protected function getPaths(FilesystemInterface $filesystem, $skipDirs)
    {
        $paths = [];

        foreach ($filesystem->listContents($this->dir, true) as $path) {
            if ($skipDirs && $path['type'] === 'dir') {
                continue;
            }

            $paths[$path['path']] = $path;
        }

        ksort($paths);

        return $paths;
    }

    /**
     * Get paths to write.
     *
     * @return array
     */
    public function getWrites()
    {
        return array_values(
            array_diff_key(
                $this->getPaths($this->master, true),
                $this->getPaths($this->slave, true)
            )
        );
    }

    /**
     * Get paths to delete.
     *
     * @return array
     */
    public function getDeletes()
    {
        return array_values(
            array_diff_key(
                $this->getPaths($this->slave, false),
                $this->getPaths($this->master, false)
            )
        );
    }

    /**
     * Get paths to update.
     *
     * @return array
     */
    public function getUpdates()
    {
        return array_values(
            array_intersect_key(
                $this->getPaths($this->master, true),
                $this->getPaths($this->slave, true)
            )
        );
    }

    /**
     * Call ->put() on $slave. Update/Write content from $master. Also sets visibility on slave.
     *
     * @param $path
     * @return void
     */
    protected function put($path)
    {
        $this->slave->put($path['path'], $this->master->read($path['path']));
    }

    /**
     * Sync any writes.
     *
     * @return $this
     */
    public function syncWrites()
    {
        foreach ($this->getWrites() as $path) {
            $this->put($path);
        }

        return $this;
    }

    /**
     * Sync any deletes.
     *
     * @return $this
     */
    public function syncDeletes()
    {
        foreach ($this->getDeletes() as $path) {
            if (!$this->slave->has($path['path'])) {
                continue;
            }

            if ($path['type'] === 'dir') {
                $this->slave->deleteDir($path['path']);
            }
            else {
                $this->slave->delete($path['path']);
            }
        }

        return $this;
    }

    /**
     * Sync any updates.
     *
     * @return $this
     */
    public function syncUpdates()
    {
        foreach ($this->getUpdates() as $path) {
            if ($this->master->getTimestamp($path) > $this->slave->getTimestamp($path)) {
                $this->put($path);
            }
        }

        return $this;
    }

    /**
     * Call $this->syncWrites(), $this->syncUpdates(), and $this->syncDeletes()
     *
     * @return $this
     */
    public function sync()
    {
        $this
            ->syncWrites()
            ->syncUpdates()
            ->syncDeletes()
        ;

        return $this;
    }
}
