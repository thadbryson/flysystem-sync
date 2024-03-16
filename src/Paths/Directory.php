<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;

readonly class Directory implements Path
{
    public string $path;

    public function __construct(
        string $path,
        public ?string $visibility,
        public ?int $lastModified
    ) {
        $this->path = PathHelper::prepare($path);
    }

    public function isChanged(?Path $target): bool
    {
        return
            $target === null ||
            $this->isFile() !== $target->isFile() ||
            $this->isDirectory() !== $target->isDirectory() ||
            $this->visibility !== $target->visibility;
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
        ];
    }

    public function isFile(): false
    {
        return false;
    }

    public function isDirectory(): true
    {
        return true;
    }
}
