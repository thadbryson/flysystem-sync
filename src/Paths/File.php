<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Traits\PathTrait;

readonly class File implements Path
{
    use PathTrait;

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
            'type'         => static::class,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'fileSize'     => $this->fileSize,
            'mimeType'     => $this->mimeType,
        ];
    }
}
