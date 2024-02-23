<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners\Contracts;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

interface Runner
{
    public function __construct(Filesystems\Extended $reader, Filesystems\Extended $writer);
}
