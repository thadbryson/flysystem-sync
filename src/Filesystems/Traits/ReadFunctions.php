<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Traits;

use League\Flysystem\DirectoryListing;
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

    public function fileExists(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    public function directoryExists(string $location): bool
    {
        return $this->filesystem->directoryExists($location);
    }

    public function pathExists(string $location): bool
    {
        return $this->filesystem->has($location);
    }

    public function has(string $location): bool
    {
        throw new \Exception;
    }

    public function read(string $location): string
    {
        return $this->filesystem->read($location);
    }

    /**
     * Only use read() or readStream()
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public function readStream(string $location): mixed
    {
        return $this->filesystem->readStream($location);
    }

    public function listContents(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        throw new \Exception;
    }

    public function lastModified(string $path): int
    {
        throw new \Exception;
    }

    public function fileSize(string $path): int
    {
        throw new \Exception;
    }

    public function mimeType(string $path): string
    {
        throw new \Exception;
    }

    public function visibility(string $path): string
    {
        throw new \Exception;
    }
}
