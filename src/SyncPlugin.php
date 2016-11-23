<?php

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

/**
 * class SyncPlugin
 *
 * @author Thad Bryson <thadbry@gmail.com>
 */
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
     * @return void
     */
    public function setFilesystem(FilesystemInterface $master)
    {
        $this->master = $master;
    }

    /**
     * Method to call on object returned from $this->handle().
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getSync';
    }

    /**
     * Run plugin.
     *
     * @param FilesystemInterface $slave
     * @param string $dir
     * @return \TCB\Flysystem\Sync
     */
    public function handle(FilesystemInterface $slave, $dir = '/')
    {
        return new Sync($this->master, $slave, $dir);
    }
}
