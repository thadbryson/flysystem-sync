<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use TCB\FlysystemSync\Filesystems\Traits\FilesystemTrait;

class Reader
{
    use FilesystemTrait;

    protected readonly Filesystem $filesystem;

    public function __construct(FilesystemAdapter $adapter)
    {
        $adapter = new ReadOnlyFilesystemAdapter($adapter);

        $this->filesystem = new Filesystem($adapter);
    }

    public function readStream(string $path): mixed
    {
        return $this->filesystem->readStream($path);
    }
}
