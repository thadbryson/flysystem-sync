<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use League\Flysystem\WhitespacePathNormalizer;

use function trim;

class Helper
{
    /**
     * Should these paths be updated?
     */
    public static function isSame(?StorageAttributes $source, ?StorageAttributes $target): bool
    {
        // Paths have to match or else there's an error.
        // If paths are different you'd DELETE one and CREATE another.
        // Types have to be the same.
        if ($source === null ||
            $target === null ||
            $source->path() !== $target->path() ||
            $source instanceof FileAttributes !== $target instanceof FileAttributes ||
            $source instanceof DirectoryAttributes !== $target instanceof DirectoryAttributes
        ) {
            return false;
        }

        // Directories can only have visibility different.
        if ($source->isDir()) {
            return true;
        }

        // Both are Files

        /**
         * @var FileAttributes $source
         * @var FileAttributes $target
         */
        return
            $source->lastModified() === $target->lastModified() &&
            $source->fileSize() === $target->fileSize() &&
            $source->mimeType() === $target->mimeType();
    }

    public static function preparePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return (new WhitespacePathNormalizer)->normalizePath($path);
    }

    public static function assertSourceTarget(?StorageAttributes $source, ?StorageAttributes $target): void
    {
        if ($source === null && $target === null) {
            throw new \Exception('');
        }
    }
}
