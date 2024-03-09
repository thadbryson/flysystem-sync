<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

use function trim;

class Helper
{
    /**
     * Should these paths be updated?
     */
    public static function isSame(StorageAttributes $source, StorageAttributes $target): bool
    {
        if ($source->path() !== $target->path() ||
            $source->type() !== $target->type() ||
            $source->lastModified() !== $target->lastModified() ||
            $source->visibility() !== $target->visibility()
        ) {
            return false;
        }

        if ($source instanceof FileAttributes &&
            $target instanceof FileAttributes
        ) {
            return $source->fileSize() === $target->fileSize() &&
                   $source->mimeType() === $target->mimeType();
        }

        return true;
    }

    public static function getBag(?StorageAttributes $source, ?StorageAttributes $target): string
    {
        $action = match (true) {
            $source !== null && $target === null       => 'create',
            $source === null && $target !== null       => 'delete',

            $source !== null && $target !== null &&
            Helper::isSame($source, $target) === false => 'update',

            default                                    => 'nothing'
        };

        $type = ($source ?? $target)->isFile() ?
            '_files' :
            '_directories';

        return $action . $type;
    }

    public static function preparePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return $path;
    }

    public static function getFile(Filesystem $filesystem, string $path): ?FileAttributes
    {
        $path = static::preparePath($path);

        if ($filesystem->fileExists($path) === false) {
            return null;
        }

        return new FileAttributes(
            $path,
            $filesystem->fileSize($path),
            $filesystem->visibility($path),
            $filesystem->lastModified($path),
            $filesystem->mimeType($path)
        );
    }

    public static function getDirectory(Filesystem $filesystem, string $path): ?DirectoryAttributes
    {
        $path = static::preparePath($path);

        if ($filesystem->directoryExists($path) === false) {
            return null;
        }

        return new DirectoryAttributes(
            $path,
            $filesystem->visibility($path),
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
