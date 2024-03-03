<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;

/**
 * Only Filesystem reading functions.
 *
 * No write functionality.
 */
class ReaderFilesystem implements FilesystemReader
{
    /**
     * Performs Filesystem operations.
     */
    protected readonly Filesystem $filesystem;

    public function __construct(Filesystem|FilesystemAdapter $adapter)
    {
        $this->filesystem = HelperFilesystem::prepareFilesystem($adapter);
    }

    public function fileExists(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    public function directoryExists(string $location): bool
    {
        return $this->filesystem->directoryExists($location);
    }

    public function has(string $location): bool
    {
        return $this->filesystem->has($location);
    }

    public function read(string $location): string
    {
        return $this->filesystem->read($location);
    }

    public function readStream(string $location)
    {
        return $this->filesystem->readStream($location);
    }

    public function listContents(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        return $this->filesystem->listContents($location, $deep);
    }

    public function lastModified(string $path): int
    {
        return $this->filesystem->lastModified($path);
    }

    public function fileSize(string $path): int
    {
        return $this->filesystem->fileSize($path);
    }

    public function mimeType(string $path): string
    {
        return $this->filesystem->mimeType($path);
    }

    public function visibility(string $path): string
    {
        return $this->filesystem->visibility($path);
    }
}
