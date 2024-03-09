<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

class Loader
{
    public static function getFile(Filesystem $filesystem, string $path): ?FileAttributes
    {
        $path = Helper::preparePath($path);

        if ($filesystem->fileExists($path) === false) {
            return null;
        }

        return new FileAttributes(
            $path,
            $filesystem->fileSize($path),
            null,
            $filesystem->lastModified($path),
            $filesystem->mimeType($path)
        );
    }

    public static function getDirectory(Filesystem $filesystem, string $path): ?DirectoryAttributes
    {
        $path = Helper::preparePath($path);

        if ($filesystem->directoryExists($path) === false) {
            return null;
        }

        return new DirectoryAttributes(
            $path,
            null,
            $filesystem->lastModified($path)
        );
    }

    public static function getPath(Filesystem $filesystem, string $path): ?StorageAttributes
    {
        return
            static::getFile($filesystem, $path) ??
            static::getDirectory($filesystem, $path);
    }
}
