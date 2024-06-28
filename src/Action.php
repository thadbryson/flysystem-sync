<?php

declare(strict_types=1);

namespace TCB\FlysystemSync;

use League\Flysystem\StorageAttributes;

readonly class Action
{
    public function __construct(
        public readonly ActionEnum $action,
        public readonly string $path,
        public readonly ?StorageAttributes $source,
    )
    {
    }
}
