<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;
use League\Flysystem\PathNormalizer;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use League\Flysystem\UrlGeneration\TemporaryUrlGenerator;

/**
 * Only Filesystem reading functions.
 *
 * No write functionality.
 */
class Reader implements FilesystemReader
{
    /**
     * Performs Filesystem operations.
     */
    protected readonly Filesystem $filesystem;

    public function __construct(
        FilesystemAdapter $adapter,
        array $config = [],
        PathNormalizer $pathNormalizer = null,
        ?PublicUrlGenerator $publicUrlGenerator = null,
        ?TemporaryUrlGenerator $temporaryUrlGenerator = null
    ) {
        $this->filesystem = new Filesystem(
            $adapter,
            $config,
            $pathNormalizer,
            $publicUrlGenerator,
            $temporaryUrlGenerator
        );
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
