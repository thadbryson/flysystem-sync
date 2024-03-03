<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;
use League\Flysystem\WhitespacePathNormalizer;

use function array_diff;

/**
 * Helper class for Filesystems.
 * Does various things with FileAttributes, StorageAttributes, etc.
 */
class HelperFilesystem
{
    public static function prepareFilesystem(Filesystem|FilesystemAdapter $adapter): Filesystem
    {
        if ($adapter instanceof Filesystem) {
            return $adapter;
        }

        return new Filesystem($adapter);
    }

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
        return static::getDifferences($source, $target) !== [];
    }

    protected static function toArray(FileAttributes|DirectoryAttributes $path): array
    {
        $result = [
            'type'         => $path instanceof FileAttributes ? 'file' : 'directory',
            'path'         => $path->path(),
            'visibility'   => $path->visibility(),
            'lastModified' => $path->lastModified(),
        ];

        if ($result['type'] === 'file') {
            $result['fileSize'] = $path->fileSize();
            $result['mimeType'] = $path->mimeType();
        }

        return $result;
    }

    public static function getDifferences(
        FileAttributes|DirectoryAttributes|null $source,
        FileAttributes|DirectoryAttributes|null $target
    ): array {
        return match (true) {
            $source === null && $target === null => [],
            $source !== null && $target === null => static::toArray($source),
            $source === null && $target !== null => static::toArray($target),

            default                              => array_diff(
                static::toArray($source),
                static::toArray($target)
            )
        };
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
    public static function loadAllPaths(FilesystemReader $filesystem, array $paths): array
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

    public static function loadPath(FilesystemReader $filesystem, string $path): FileAttributes|DirectoryAttributes|null
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
