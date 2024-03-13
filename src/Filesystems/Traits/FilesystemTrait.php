<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Traits;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

/**
 * @property-read Filesystem $filesystem
 */
trait FilesystemTrait
{
    abstract public function __construct(FilesystemAdapter $adapter);

    public function getFile(string $path): ?File
    {
        if ($this->filesystem->fileExists($path) === false) {
            return null;
        }

        return new File(
            $path,
            $this->filesystem->visibility($path),
            $this->filesystem->lastModified($path),
            $this->filesystem->fileSize($path),
            $this->filesystem->mimeType($path)
        );
    }

    public function getDirectory(string $path): ?Directory
    {
        if ($this->filesystem->directoryExists($path) === false) {
            return null;
        }

        return new Directory(
            $path,
            $this->filesystem->visibility($path),
            $this->filesystem->lastModified($path)
        );
    }

    public function getPath(string $path): ?Path
    {
        return
            $this->getFile($path) ??
            $this->getDirectory($path);
    }

    public function getDirectoryContents(string $path): ?array
    {
        if ($this->filesystem->directoryExists($path) === false) {
            return null;
        }

        $contents = [];

        $this->filesystem
            ->listContents($path, FilesystemReader::LIST_DEEP)
            ->map(function (StorageAttributes $found) use (&$contents): void {
                $path = PathHelper::prepare($found->path());

                $contents[$path] = $found;
            });

        return $contents;
    }
}
