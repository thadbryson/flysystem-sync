<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Enums;

namespace TCB\FlysystemSync\Action\Enums;

use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

enum ActionEnum
{
    case CREATE_DIRECTORY;
    case DELETE_DIRECTORY;
    case UPDATE_DIRECTORY;
    case NOTHING_DIRECTORY;
    case CREATE_FILE;
    case DELETE_FILE;
    case UPDATE_FILE;
    case NOTHING_FILE;

    public static function getType(File|Directory|null $source, File|Directory|null $target): ?self
    {
        if ($source === null && $target === null) {
            throw new \InvalidArgumentException('');
        }

        $is_file = $source ?? $target;
        $is_file = $is_file->isFile();

        return match (true) {
            $source !== null && $target === null => $is_file ? self::CREATE_FILE : self::CREATE_DIRECTORY,
            $source === null && $target !== null => $is_file ? self::DELETE_FILE : self::DELETE_DIRECTORY,

            // Both not NULL
            $source->isDifferent($target)        => $is_file ? self::UPDATE_FILE : self::UPDATE_DIRECTORY,
            default                              => $is_file ? self::NOTHING_FILE : self::NOTHING_DIRECTORY,
        };
    }
}
