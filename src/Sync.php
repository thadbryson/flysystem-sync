<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

class Sync
{
    /**
     * Source filesystem.
     *
     * @var Filesystem
     */
    protected $source;

    /**
     * Target filesystem.
     *
     * @var Filesystem
     */
    protected $target;

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

    public function __construct(Filesystem $source, Filesystem $target, array $config = [], string $directory = '/')
    {
        $this->source = $source;
        $this->target  = $target;

        $this->config = $config;

        $this->util = new Util($source, $target, $directory);
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
    public function syncWrites(): Sync
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
    public function syncUpdates(): Sync
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
    public function syncDeletes(): Sync
    {
        foreach ($this->util->getDeletes() as $path) {

            // A dir delete may of deleted this path already.
            if ($path->isFile()) {
                $this->target->delete($path->path());
            }
            // A dir? They're deleted a special way.
            elseif ($path->isDir()) {
                $this->target->deleteDirectory($path->path());
            }
        }

        return $this;
    }

    /**
     * Call $this->syncWrites(), $this->syncUpdates(), and $this->syncDeletes()
     *
     * @return $this
     */
    public function sync(): Sync
    {
        return $this
            ->syncWrites()
            ->syncUpdates()
            ->syncDeletes();
    }

    /**
     * Call ->put() on $target. Update/Write content from $source. Also sets visibility on target.
     */
    protected function put(StorageAttributes $path): void
    {
        // Otherwise create or update the file.
        if ($path->isFile()) {
            $contents = $this->source->readStream($path->path());

            $this->target->writeStream($path->path(), $contents, $this->config);
        }
        // A dir? Create it.
        elseif ($path->isDir()) {
            $this->target->createDirectory($path->path(), $this->config);
        }
    }
}
