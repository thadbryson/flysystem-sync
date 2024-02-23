<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

use function array_filter;
use function array_merge;
use function implode;

class Extended extends Filesystem
{
    public function get(string $path): File | Directory | null
    {
        return $this->file($path) ?? $this->directory($path) ?? null;
    }

    public function file(string $path): ?File
    {
        if ($this->fileExists($path) === false) {
            return null;
        }

        return new File(
            $path,
            $this->visibility($path),
            $this->lastModified($path),
            $this->fileSize($path),
            $this->mimeType($path),
        );
    }

    public function directory(string $path): ?Directory
    {
        if ($this->directoryExists($path) === false) {
            return null;
        }

        return new Directory(
            $path,
            $this->visibility($path),
            $this->lastModified($path),
        );
    }

    public function directoryContents(string $path): ?array
    {
        $directory = $this->directory($path);

        if ($directory === null) {
            return null;
        }

        $contents = [
            $path => $directory,    // @todo Does ->listContents() include the root directory given?
        ];

        $this
            ->listContents($path, FilesystemReader::LIST_DEEP)
            ->map(
                function (StorageAttributes $current) use (&$contents): void {
                    //Create from StorageAttributes
                    $contents[$current->path()] = $current instanceof FileAttributes ?
                        new File(
                            $current->path(),
                            $current->visibility(),
                            $current->lastModified(),
                            $current->fileSize(),
                            $current->mimeType()
                        )

                        :

                        new Directory(
                            $current->path(),
                            $current->visibility(),
                            $current->lastModified()
                        );
                }
            );

        return $contents;
    }

    /**
     *
     * @throws \Exception
     */
    public function fileOrFail(string $path): File
    {
        return $this->file($path) ?? throw new \Exception;
    }

    /**
     *
     * @throws \Exception
     */
    public function directoryOrFail(string $path): Directory
    {
        return $this->directory($path) ?? throw new \Exception;
    }

    /**
     *
     * @throws \Exception
     */
    public function directoryContentsOrFail(string $path): array
    {
        return $this->directoryContents($path) ?? throw new \Exception;
    }

    /**
     * @return File[]|Directory[]
     */
    public function load(Path ...$paths): array
    {
        $all = [];

        // Get all targets
        foreach ($paths as $current) {
            $found = $this->get($current->path);

            $all[$current->path] = $found;

            // Directory contents?
            if ($found !== null && $found->is_directory) {
                $all = array_merge(
                    $all,

                    // Only returns contents that exist.
                    $this->directoryContents($current->path) ?? []
                );
            }
        }

        return $all;
    }

    /**
     * @return File[]|Directory[]
     */
    public function loadOrFail(Path ...$paths): array
    {
        $all   = $this->load(...$paths);
        $fails = array_keys(
        // Where path found is NULL
            array_filter($all, fn (?Path $value): bool => $value === null)
        );

        if ($fails !== []) {
            throw new \Exception('Failed to load: ' . implode(', ', $fails));
        }

        return $all;
    }
}
