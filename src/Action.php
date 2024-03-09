<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

interface Action
{
    public function __construct(
        Filesystem $reader,
        Filesystem $writer,
        ?StorageAttributes $source,
        ?StorageAttributes $target
    );

    public function execute();

    public function isSuccess(): bool;
}
