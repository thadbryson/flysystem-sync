<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Contracts;

use League\Flysystem\FilesystemAdapter;

interface SyncFilesystem
{
    public function __construct(FilesystemAdapter $adapter);

    public function readStream(string $path): mixed;
}
