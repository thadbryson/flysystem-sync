<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystems;

use TCB\FlysystemSync\Filesystems\Contracts\SyncFilesystem;
use TCB\FlysystemSync\Filesystems\Writer;
use Tests\Support\TestAdapter;

trait WriterTrait
{
    use SyncFilesystemTrait;

    protected function getSyncFilesystem(TestAdapter $adapter): SyncFilesystem
    {
        return new Writer($adapter);
    }
}
