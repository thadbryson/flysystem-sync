<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Exceptions\MethodNotAllowed;
use TCB\FlysystemSync\Filesystems\Contracts\SyncFilesystem;
use TCB\FlysystemSync\Filesystems\Traits\FilesystemTrait;

class Writer implements SyncFilesystem
{
    use FilesystemTrait;

    public readonly Filesystem $filesystem;

    public readonly FilesystemAdapter $adapter;

    public function __construct(FilesystemAdapter $adapter)
    {
        $this->adapter    = $adapter;
        $this->filesystem = new Filesystem($this->adapter);
    }

    public function readStream(string $path): mixed
    {
        throw new MethodNotAllowed(static::class, 'readStream');
    }
}
