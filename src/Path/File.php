<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

class File extends AbstractPath
{
    public static function fromAttributes($attributes): static
    {
        return new static(
            $attributes->path(),
            $attributes->visibility(),
            $attributes->lastModified(),
        );
    }
}
