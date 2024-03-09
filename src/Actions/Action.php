<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions;

use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

class Action implements \TCB\FlysystemSync\Action
{
    public function __construct(
        Filesystem $reader,
        Filesystem $writer,
        public ?StorageAttributes $source,
        public ?StorageAttributes $target
    ) {
    }

    public function execute()
    {
    }

    public function isSuccess(): bool
    {
        return true;
    }
}
