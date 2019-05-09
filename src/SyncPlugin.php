<?php

declare(strict_types = 1);

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use RuntimeException;

class SyncPlugin implements PluginInterface
{
    /**
     * Master filesystem.
     *
     * @var FilesystemInterface
     */
    protected $master;

    /**
     * Set master Filesystem.
     *
     * @param FilesystemInterface $master
     * @return $this
     */
    public function setFilesystem(FilesystemInterface $master)
    {
        $this->master = $master;

        return $this;
    }

    /**
     * Method to call on object returned from $this->handle().
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'getSync';
    }

    /**
     * Run plugin.
     *
     * @param FilesystemInterface $slave
     * @param string              $dir
     * @return \TCB\Flysystem\Sync
     * @throws RuntimeException
     */
    public function handle(FilesystemInterface $slave, string $dir = '/'): Sync
    {
        if ($this->master === null) {

            throw new RuntimeException(sprintf('Must set \$master %s directory with %s->setFilesystem(\$master)',
                FilesystemInterface::class, static::class));
        }

        return new Sync($this->master, $slave, $dir);
    }
}
