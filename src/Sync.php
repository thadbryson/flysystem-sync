<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

class Sync
{
    /**
     * Master filesystem.
     *
     * @var Filesystem
     */
    protected $master;

    /**
     * Slave filesystem.
     *
     * @var Filesystem
     */
    protected $slave;

    /**
     * Configuration for Adapter.
     *
     * @var array
     */
    protected $config;

    /**
     * Util object for getting WRITE, UPDATE, and DELETE paths.
     *
     * @var Util
     */
    protected $util;

    public function __construct(Filesystem $master, Filesystem $slave, array $config = [], string $dir = '/')
    {
        $this->master = $master;
        $this->slave  = $slave;

        $this->config = $config;

        $this->util = new Util($master, $slave, $dir);
    }

    /**
     * Get Util helper object used for getting WRITE, UPDATE, and DELETE paths.
     */
    public function getUtil(): Util
    {
        return $this->util;
    }

    /**
     * Sync any writes.
     *
     * @return $this
     */
    public function syncWrites()
    {
        foreach ($this->util->getWrites() as $path) {
            $this->put($path);
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
        foreach ($this->util->getUpdates() as $path) {
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
        foreach ($this->util->getDeletes() as $path) {

            // A dir delete may of deleted this path already.
            if ($path->isFile()) {
                $this->slave->delete($path->path());
            }
            // A dir? They're deleted a special way.
            elseif ($path->isDir()) {
                $this->slave->deleteDirectory($path->path());
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
        return $this
            ->syncWrites()
            ->syncUpdates()
            ->syncDeletes();
    }

    /**
     * Call ->put() on $slave. Update/Write content from $master. Also sets visibility on slave.
     */
    protected function put(StorageAttributes $path): void
    {
        // Otherwise create or update the file.
        if ($path->isFile()) {
            $contents = $this->master->readStream($path->path());

            $this->slave->writeStream($path->path(), $contents, $this->config);
        }
        // A dir? Create it.
        elseif ($path->isDir()) {
            $this->slave->createDirectory($path->path(), $this->config);
        }
    }
}
