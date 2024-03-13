<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Traits\CompareTrait;

readonly class File implements Path
{
    use CompareTrait;

    public string $path;

    public function __construct(
        string $path,
        public ?string $visibility,
        public ?int $lastModified,
        public ?int $fileSize,
        public ?string $mimeType
    ) {
        $this->path = PathHelper::prepare($path);
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'fileSize'     => $this->fileSize,
            'mimeType'     => $this->mimeType,
        ];
    }

    public function clone(): static
    {
        return new static(
            $this->path,
            $this->visibility,
            $this->lastModified,
            $this->fileSize,
            $this->mimeType,
        );
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
