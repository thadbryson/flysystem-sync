<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Paths\Contracts\Path;

enum Action
{
    case CREATE;
    case UPDATE;
    case NOTHING;

    /**
     *
     * @throws \Exception
     */
    public static function get(Path $source, ?Path $target): self
    {
        if ($target === null) {
            return self::CREATE;
        }

        return $source->getDifferences($target) !== [] ?
            self::UPDATE :
            self::NOTHING;
    }
}
