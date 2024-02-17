<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Attributes;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

class AttributesCompare
{
    public static function isSame(
        StorageAttributes $path,
        StorageAttributes $compare
    ): bool {
        return $compare instanceof FileAttributes ?
            static::isSameFile($path, $compare) :
            static::isSameDirectory($path, $compare);
    }

    protected static function isSameFile(StorageAttributes $info, StorageAttributes $file): bool
    {
        if (static::isSamePath(true, $info, $file) === false) {
            return false;
        }

        return
            $info instanceof FileAttributes &&
            $file instanceof FileAttributes &&

            // File specific
            $info->fileSize() === $file->fileSize() &&
            $info->mimeType() === $file->mimeType();
    }

    protected static function isSameDirectory(StorageAttributes $info, StorageAttributes $directory): bool
    {
        if (static::isSamePath(false, $info, $directory) === false) {
            return false;
        }

        return
            $info instanceof DirectoryAttributes &&
            $directory instanceof DirectoryAttributes;
    }

    protected static function isSamePath(bool $is_file, StorageAttributes $info, StorageAttributes $compare): bool
    {
        if (
            $info->isFile() === !$is_file ||
            $info->isDir() === $is_file
        ) {
            return false;
        }

        return
            $info->isFile() === $compare->isFile() &&
            $info->isDir() === $compare->isDir() &&
            $info->path() === $compare->path() &&
            $info->lastModified() === $compare->lastModified() &&
            $info->visibility() === $compare->visibility();
    }
}
