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
        return match (true) {
            $target === null                   => self::CREATE,
            $source->isSame($target) === false => self::UPDATE,
            default                            => self::NOTHING,
        };
    }
}
