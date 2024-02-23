<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Paths\Contracts\Path as PathContract;
use TCB\FlysystemSync\Paths\Traits\Path;

readonly class Directory implements PathContract
{
    use Path;

    public function __construct(
        string $path,
        ?string $visibility,
        ?int $last_modified
    ) {
        $this->constructSetup($path, $visibility, $last_modified, false);
    }
}
