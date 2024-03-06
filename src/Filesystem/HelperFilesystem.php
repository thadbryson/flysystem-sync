<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function array_diff;

/**
 * Helper class for Filesystems.
 * Does various things with File, Directory, etc.
 */
class HelperFilesystem
{
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
}
