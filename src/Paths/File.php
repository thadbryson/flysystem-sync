<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;

readonly class File implements Path
{
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

    public function isChanged(?Path $target): bool
    {
        return
            $target === null ||
            $this->isFile() !== $target->isFile() ||
            $this->isDirectory() !== $target->isDirectory() ||
            $this->visibility !== $target->visibility ||
            $this->lastModified > $target->lastModified ||
            $this->fileSize !== $target->fileSize ||
            $this->mimeType !== $target->mimeType;
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

    public function isFile(): true
    {
        return true;
    }

    public function isDirectory(): false
    {
        return false;
    }
}
