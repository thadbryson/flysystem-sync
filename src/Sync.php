<?php

namespace TCB\Flysystem;

use League\Flysystem\FilesystemInterface;

class Sync
{
    protected $master;
    protected $slave;

    protected $dir;



    public function __construct(FilesystemInterface $master, FilesystemInterface $slave, $dir = '/')
    {
        $this->master = $master;
        $this->slave  = $slave;

        $this->dir = $dir;
    }

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

    public function getWrites()
    {
        return array_diff_key(
            $this->getPaths($this->master, true),
            $this->getPaths($this->slave, true)
        );
    }

    public function getDeletes()
    {
        return array_diff_key(
            $this->getPaths($this->slave, false),
            $this->getPaths($this->master, false)
        );
    }

    public function getUpdates()
    {
        return array_intersect_key(
            $this->getPaths($this->master, true),
            $this->getPaths($this->slave, true)
        );
    }

    protected function put($path)
    {
        $this->slave->put($path['path'], $this->master->read($path['path']));
    }

    public function syncWrites()
    {
        foreach ($this->getWrites() as $path) {
            $this->put($path);
        }

        return $this;
    }

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

    public function syncUpdates()
    {
        foreach ($this->getUpdates() as $path) {
            if ($this->master->getTimestamp($path) > $this->slave->getTimestamp($path)) {
                $this->put($path);
            }
        }

        return $this;
    }

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
