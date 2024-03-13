<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use League\Flysystem\WhitespacePathNormalizer;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\File;

use function trim;

class Helper
{
    /**
     * Should these paths be updated?
     */
    public static function isSame(?Path $source, ?Path $target): bool
    {
        // Paths have to match or else there's an error.
        // If paths are different you'd DELETE one and CREATE another.
        // Types have to be the same.
        if ($source === null ||
            $target === null ||
            $source->path !== $target->path ||
            $source->isFile() !== $target->isFile() ||
            $source->isDirectory() !== $target->isDirectory()
        ) {
            return false;
        }

        // Directories can only have visibility different.
        if ($source->isDirectory()) {
            return true;
        }

        // Both are Files

        /**
         * @var File $source
         * @var File $target
         */
        return
            $source->lastModified === $target->lastModified &&
            $source->fileSize === $target->fileSize &&
            $source->mimeType === $target->mimeType;
    }

    public static function preparePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return (new WhitespacePathNormalizer)->normalizePath($path);
    }
}
