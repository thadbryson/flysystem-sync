<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use TCB\FlysystemSync\Filesystems\Contracts\SyncFilesystem;
use TCB\FlysystemSync\Filesystems\Traits\FilesystemTrait;

class Reader implements SyncFilesystem
{
    use FilesystemTrait;

    protected readonly Filesystem $filesystem;

    public readonly FilesystemAdapter $adapter;

    public function __construct(FilesystemAdapter $adapter)
    {
        $this->adapter    = new ReadOnlyFilesystemAdapter($adapter);
        $this->filesystem = new Filesystem($this->adapter);
    }

    public function readStream(string $path): mixed
    {
        return $this->filesystem->readStream($path);
    }
}
