<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Type;

use TCB\FlysystemSync\Paths\Contract;
use TCB\FlysystemSync\Paths\Traits;

readonly class File implements Contract\Path
{
    use Traits\Path;

    public function isFile(): bool
    {
        return true;
    }

    public function isDirectory(): false
    {
        return false;
    }
}
