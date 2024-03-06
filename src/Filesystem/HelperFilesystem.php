<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use League\Flysystem\WhitespacePathNormalizer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function array_diff;
use function trim;

/**
 * Helper class for Filesystems.
 * Does various things with File, Directory, etc.
 */
class HelperFilesystem
{
    public static function prepareReader(FilesystemAdapter|ReaderFilesystem $reader): ReaderFilesystem
    {
        if ($reader instanceof ReaderFilesystem) {
            return $reader;
        }

        $reader = new ReadOnlyFilesystemAdapter($reader);

        return new ReaderFilesystem($reader);
    }

    public static function prepareFilesystem(Filesystem|FilesystemAdapter $adapter): Filesystem
    {
        if ($adapter instanceof Filesystem) {
            return $adapter;
        }

        return new Filesystem($adapter);
    }

    public static function preparePath(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return (new WhitespacePathNormalizer)->normalizePath($path);
    }

    public static function isValidType(mixed $value, bool $is_file): bool
    {
        return $is_file ?
            $value instanceof File :
            $value instanceof Directory;
    }

    /**
     * Are these paths the same?
     * Compares: last_modified, visibility, type (file/directory),
     *  if files compares file_size and mime_type
     *
     * @throws \Exception - Different path strings
     */
    public static function isSame(
        File|Directory $source,
        File|Directory $target
    ): bool {
        return static::getDifferences($source, $target) !== [];
    }

    public static function getDifferences(
        File|Directory|null $source,
        File|Directory|null $target
    ): array {
        return match (true) {
            $source === null && $target === null => [],

            $source !== null && $target === null => $source->toArray(),
            $source === null && $target !== null => $target->toArray(),

            default                              => array_diff(
                $source->toArray(),
                $target->toArray()
            )
        };
    }

    private static function toArray(File|Directory $path): array
    {
        $result = [
            'path'         => $path->path,
            'type'         => $path->type,
            'visibility'   => $path->visibility,
            'lastModified' => $path->lastModified,
        ];

        if ($path->is_file === true) {
            $result['fileSize'] = $path->fileSize;
            $result['mimeType'] = $path->mimeType;
        }

        return $result;
    }
}
