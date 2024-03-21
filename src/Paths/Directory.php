<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use League\Flysystem\DirectoryAttributes;
use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Traits\PathTrait;

readonly class Directory implements Path
{
    use PathTrait;

    public string $path;

    public function __construct(
        string $path,
        public bool $exists,
        public ?string $visibility,
        public ?int $lastModified
    ) {
        $this->path = PathHelper::prepare($path);
    }

    public static function make(DirectoryAttributes $attributes): Directory
    {
        return new Directory(
            $attributes->path(),
            true,
            $attributes->visibility(),
            $attributes->lastModified()
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
        ];
    }

    public function withPath(string $path): static
    {
        return new static(
            $path,
            $this->exists,
            $this->visibility,
            $this->lastModified,
        );
    }
}
