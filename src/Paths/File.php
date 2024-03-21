<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use League\Flysystem\FileAttributes;
use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Traits\PathTrait;

/**
 * @property-read  int|null   $fileSize,
 * @property-read string|null $mimeType
 */
readonly class File implements Path
{
    use PathTrait;

    public string $path;

    public function __construct(
        string $path,
        public bool $exists,
        public ?string $visibility,
        public ?int $lastModified,
        public ?int $fileSize,
        public ?string $mimeType
    ) {
        $this->path = PathHelper::prepare($path);
    }

    public static function make(FileAttributes $attributes): File
    {
        return new File(
            $attributes->path(),
            true,
            $attributes->visibility(),
            $attributes->lastModified(),
            $attributes->fileSize(),
            $attributes->mimeType()
        );
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'exists'       => $this->exists,
            'type'         => static::class,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'fileSize'     => $this->fileSize,
            'mimeType'     => $this->mimeType,
        ];
    }

    public function withPath(string $path): static
    {
        return new static(
            $path,
            $this->exists,
            $this->visibility,
            $this->lastModified,
            $this->fileSize,
            $this->mimeType,
        );
    }
}
