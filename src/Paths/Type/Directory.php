<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Type;

use TCB\FlysystemSync\Paths\Contract;
use TCB\FlysystemSync\Paths\Traits;

readonly class Directory implements Contract\Path
{
    use Traits\Path;

    public function isFile(): false
    {
        return false;
    }

    public function isDirectory(): true
    {
        return true;
    }
}
