<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Traits;

use League\Flysystem\FilesystemReader;

trait ReadFunctions
{
    /**
     * ONLY call the reader functions on this Filesystem.
     */
    public readonly FilesystemReader $filesystem;

    public function __construct(FilesystemReader $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function hasFile(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    public function hasDirectory(string $location): bool
    {
        return $this->filesystem->directoryExists($location);
    }

    public function hasPath(string $location): bool
    {
        return $this->filesystem->has($location);
    }

    public function read(string $location): mixed
    {
        return $this->filesystem->readStream($location);
    }
}
