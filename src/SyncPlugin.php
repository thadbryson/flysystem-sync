<?php

namespace TCB\Flysystem;

use TCB\Flysystem\Sync;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

class SyncPlugin implements PluginInterface
{
    protected $master;

    public function setFilesystem(FilesystemInterface $master)
    {
        $this->master = $master;
    }

    public function getMethod()
    {
        return 'getSync';
    }

    public function handle(FilesystemInterface $slave, $dir = '/')
    {
        return new Sync($this->master, $slave, $dir);
    }
}
