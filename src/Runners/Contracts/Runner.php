<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners\Contracts;

use TCB\FlysystemSync\Filesystems;

interface Runner
{
    public function __construct(Filesystems\Extended $reader, Filesystems\Extended $writer);
}
