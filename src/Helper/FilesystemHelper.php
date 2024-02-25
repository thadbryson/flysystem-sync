<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helper;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\WhitespacePathNormalizer;

class FilesystemHelper
{
    /**
     * Are these paths the same?
     * Compares: last_modified, visibility, type (file/directory),
     *  if files compares file_size and mime_type
     *
     * @throws \Exception - Different path strings
     */
    public static function isSame(
        FileAttributes|DirectoryAttributes $source,
        FileAttributes|DirectoryAttributes $target
    ): bool {
        if ($source->path() !== $target->path()) {
            throw new \Exception;
        }

        // If files, compare filesize and mimetype.
        if ($source->isFile() &&
            $target->isFile() &&
            (
                $source->fileSize() !== $target->fileSize() ||
                $source->mimeType() !== $target->mimeType()
            )
        ) {
            return false;
        }

        return
            $source->lastModified() === $target->lastModified() &&
            $source->visibility() === $target->visibility() &&
            $source->type() === $target->type();
    }

    public static function preparePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return (new WhitespacePathNormalizer)->normalizePath($path);
    }

    /**
     *
     * @return FileAttributes[]|DirectoryAttributes[]|null[]
     */
    public static function loadAllPaths(Filesystem $filesystem, array $paths): array
    {
        $all = [];

        foreach ($paths as $path) {
            $path = static::preparePath($path);

            $all[$path] = static::loadPath($filesystem, $path);

            if ($all[$path] instanceof DirectoryAttributes) {
                $filesystem
                    ->listContents($path, FilesystemReader::LIST_DEEP)
                    ->sortByPath()
                    ->filter(function (FileAttributes|DirectoryAttributes $content) use (&$all) {
                        $path_current = static::preparePath($content->path());

                        $all[$path_current] = $content;
                    });
            }
        }

        return $all;
    }

    public static function loadPath(Filesystem $filesystem, string $path): FileAttributes|DirectoryAttributes|null
    {
        $path = static::preparePath($path);

        // a file?
        if ($filesystem->fileExists($path) === true) {
            return new FileAttributes(
                $path,
                $filesystem->fileSize($path),
                $filesystem->visibility($path),
                $filesystem->lastModified($path),
                $filesystem->mimeType($path),
            );
        }
        // a directory?
        elseif ($filesystem->directoryExists($path) === true) {
            return new DirectoryAttributes(
                $path,
                $filesystem->visibility($path),
                $filesystem->lastModified($path)
            );
        }

        // Not found on filesystem
        return null;
    }
}
