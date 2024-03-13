<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Helpers\Helper;
use TCB\FlysystemSync\Paths\Contracts\Path;

enum Action
{
    case CREATE_FILE;
    case DELETE_FILE;
    case UPDATE_FILE;
    case NOTHING_FILE;
    case CREATE_DIRECTORY;
    case DELETE_DIRECTORY;
    case UPDATE_DIRECTORY;
    case NOTHING_DIRECTORY;

    /**
     *
     * @throws \Exception
     */
    public static function get(?Path $source, ?Path $target): self
    {
        if ($source === null && $target === null) {
            throw new \Exception('');
        }

        return match (true) {
            Helper::isSame($source, $target)     => $source->isFile() ?
                self::NOTHING_FILE :
                self::NOTHING_DIRECTORY,

            $source !== null && $target === null => $source->isFile() ?
                self::CREATE_FILE :
                self::CREATE_DIRECTORY,

            $source === null && $target !== null => $target->isFile() ?
                self::DELETE_FILE :
                self::DELETE_DIRECTORY,

            default                              => $source->isFile() ?
                self::UPDATE_FILE :
                self::UPDATE_DIRECTORY,
        };
    }
}
