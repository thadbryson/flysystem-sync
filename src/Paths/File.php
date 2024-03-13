<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\Helper;
use TCB\FlysystemSync\Paths\Contracts\Path;

readonly class File implements Path
{
    public string $path;

    public function __construct(
        string $path,
        public ?int $fileSize,
        public ?string $visibility,
        public ?int $lastModified,
        public ?string $mimeType
    ) {
        $this->path = Helper::preparePath($path);
    }

    public function isFile(): bool
    {
        return true;
    }

    public function isDirectory(): bool
    {
        return false;
    }
}
