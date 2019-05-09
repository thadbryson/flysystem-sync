<?php

declare(strict_types = 1);

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;

class Sync
{
    /**
     * Master filesystem.
     *
     * @var FilesystemInterface
     */
    protected $master;

    /**
     * Slave filesystem.
     *
     * @var FilesystemInterface
     */
    protected $slave;

    /**
     * Util object for getting WRITE, UPDATE, and DELETE paths.
     *
     * @var Util
     */
    protected $util;

    /**
     * Sync constructor.
     *
     * @param FilesystemInterface $master
     * @param FilesystemInterface $slave
     * @param string              $dir = '/'
     */
    public function __construct(FilesystemInterface $master, FilesystemInterface $slave, string $dir = '/')
    {
        $this->master = $master;
        $this->slave  = $slave;

        $this->util = new Util($master, $slave, $dir);
    }

    /**
     * Get Util helper object used for getting WRITE, UPDATE, and DELETE paths.
     *
     * @return Util
     */
    public function getUtil(): Util
    {
        return $this->util;
    }

    /**
     * Call ->put() on $slave. Update/Write content from $master. Also sets visibility on slave.
     *
     * @param array $path
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function put(array $path): bool
    {
        // A dir? Create it.
        if ($path['type'] === 'dir') {
            return $this->slave->createDir($path['path']);
        }

        // Otherwise create or update the file.
        return $this->slave->putStream($path['path'], $this->master->readStream($path['path']));
    }

    /**
     * Sync any writes.
     *
     * @return $this
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function syncWrites()
    {
        foreach ($this->util->getWrites() as $path) {
            $this->put($path);
        }

        return $this;
    }

    /**
     * Sync any deletes.
     *
     * @return $this
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function syncDeletes()
    {
        foreach ($this->util->getDeletes() as $path) {

            // A dir delete may of deleted this path already.
            if ($this->slave->has($path['path']) === false) {
                continue;
            }
            // A dir? They're deleted a special way.
            elseif ($path['type'] === 'dir') {
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
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function syncUpdates()
    {
        foreach ($this->util->getUpdates() as $path) {
            $this->put($path);
        }

        return $this;
    }

    /**
     * Call $this->syncWrites(), $this->syncUpdates(), and $this->syncDeletes()
     *
     * @return $this
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function sync()
    {
        return $this
            ->syncWrites()
            ->syncUpdates()
            ->syncDeletes();
    }
}
