<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

class Loader
{
    public static function getFile(Filesystem $filesystem, string $path): ?File
    {
        if ($filesystem->fileExists($path) === false) {
            return null;
        }

        return new File(
            $path,
            $filesystem->fileSize($path),
            $filesystem->visibility($path),
            $filesystem->lastModified($path),
            $filesystem->mimeType($path)
        );
    }

    public static function getDirectory(Filesystem $filesystem, string $path): ?Directory
    {
        if ($filesystem->directoryExists($path) === false) {
            return null;
        }

        return new Directory(
            $path,
            $filesystem->visibility($path),
            $filesystem->lastModified($path)
        );
    }

    public static function getPath(Filesystem $filesystem, string $path): ?Path
    {
        return
            static::getFile($filesystem, $path) ??
            static::getDirectory($filesystem, $path);
    }

    public static function getDirectoryContents(Filesystem $filesystem, string $path): ?array
    {
        if ($filesystem->directoryExists($path) === false) {
            return null;
        }

        $contents = [];

        $filesystem
            ->listContents($path, FilesystemReader::LIST_DEEP)
            ->map(function (StorageAttributes $found) use (&$contents): void {
                $path = Helper::preparePath($found->path());

                $contents[$path] = $found;
            });

        return $contents;
    }
}
