<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\DirectoryAttributes;

class Directory extends AbstractPath
{
    public static function fromAttributes(DirectoryAttributes $attributes): static
    {
        return new static(
            $attributes->path(),
            $attributes->visibility(),
            $attributes->lastModified()
        );
    }
}
