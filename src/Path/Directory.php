<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\DirectoryAttributes;

use function trim;

class Directory
{
    public readonly string $path;

    public readonly string $type;

    public readonly bool $is_file;

    public readonly bool $is_directory;

    public function __construct(
        string $path,
        public readonly ?string $visibility = null,
        public readonly ?int $lastModified = null
    ) {
        $this->path = trim(trim($path), '/');

        $this->type         = 'directory';
        $this->is_file      = false;
        $this->is_directory = true;
    }

    public static function fromAttributes(DirectoryAttributes $attributes): static
    {
        return new static(
            $attributes->path(),
            $attributes->visibility(),
            $attributes->lastModified()
        );
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'type'         => $this->type,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
        ];
    }
}
