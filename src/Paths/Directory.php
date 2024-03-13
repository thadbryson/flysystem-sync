<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Traits\CompareTrait;

readonly class Directory implements Path
{
    use CompareTrait;

    public string $path;

    public function __construct(
        string $path,
        public ?string $visibility,
        public ?int $lastModified
    ) {
        $this->path = PathHelper::prepare($path);
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
        ];
    }

    public function clone(): static
    {
        return new static(
            $this->path,
            $this->visibility,
            $this->lastModified,
        );
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
