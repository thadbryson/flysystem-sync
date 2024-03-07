<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;
use League\Flysystem\PathNormalizer;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use League\Flysystem\UrlGeneration\TemporaryUrlGenerator;
use TCB\FlysystemSync\Filesystem\Traits\LoaderTrait;

/**
 * Only Filesystem reading functions.
 *
 * No write functionality.
 */
class Reader implements FilesystemReader
{
    use LoaderTrait;

    /**
     * Performs Filesystem operations.
     */
    protected readonly Filesystem $reader;

    public function __construct(
        FilesystemAdapter $adapter,
        array $config = [],
        PathNormalizer $pathNormalizer = null,
        ?PublicUrlGenerator $publicUrlGenerator = null,
        ?TemporaryUrlGenerator $temporaryUrlGenerator = null,
    ) {
        $this->reader = new Filesystem(
            new ReadOnlyFilesystemAdapter($adapter),
            $config,
            $pathNormalizer,
            $publicUrlGenerator,
            $temporaryUrlGenerator,
        );
    }

    public function fileExists(string $location): bool
    {
        return $this->reader->fileExists($location);
    }

    public function directoryExists(string $location): bool
    {
        return $this->reader->directoryExists($location);
    }

    public function has(string $location): bool
    {
        return $this->reader->has($location);
    }

    public function read(string $location): string
    {
        return $this->reader->read($location);
    }

    public function readStream(string $location)
    {
        return $this->reader->readStream($location);
    }

    public function listContents(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        return $this->reader->listContents($location, $deep);
    }

    public function lastModified(string $path): int
    {
        return $this->reader->lastModified($path);
    }

    public function fileSize(string $path): int
    {
        return $this->reader->fileSize($path);
    }

    public function mimeType(string $path): string
    {
        return $this->reader->mimeType($path);
    }

    public function visibility(string $path): string
    {
        return $this->reader->visibility($path);
    }
}
