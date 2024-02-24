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
        return (new WhitespacePathNormalizer)->normalizePath($path);
    }

    public static function loadAllPaths(Filesystem $filesystem, array $paths): array
    {
        $all = [];

        foreach ($paths as $path) {
            $all = array_merge($all, static::loadAllPaths($filesystem, $path));
        }

        return $all;
    }

    protected static function loadPath(Filesystem $filesystem, string $path): array
    {
        $path = static::preparePath($path);

        // a file?
        if ($filesystem->fileExists($path) === true) {
            return [
                $path => new FileAttributes(
                    $path,
                    $filesystem->fileSize($path),
                    $filesystem->visibility($path),
                    $filesystem->lastModified($path),
                    $filesystem->mimeType($path),
                ),
            ];
        }
        // a directory?
        elseif ($filesystem->directoryExists($path) === true) {
            // @todo does this create a duplicate path?
            $directory = [
                $path => new DirectoryAttributes(
                    $path,
                    $filesystem->visibility($path),
                    $filesystem->lastModified($path)
                ),
            ];

            $filesystem
                ->listContents($path, FilesystemReader::LIST_DEEP)
                ->sortByPath()
                ->filter(function (FileAttributes|DirectoryAttributes $content) use (&$directory) {
                    $directory[$content->path()] = $content;
                });

            return $directory;
        }

        // Not found on filesystem
        return [];
    }
}
