<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\Helper;
use TCB\FlysystemSync\Paths\Contracts\Path;

readonly class Directory implements Path
{
    public string $path;

    public function __construct(
        string $path,
        public ?string $visibility,
        public ?int $lastModified
    ) {
        $this->path = Helper::preparePath($path);
    }

    public function isFile(): bool
    {
        return false;
    }

    public function isDirectory(): bool
    {
        return true;
    }
}
