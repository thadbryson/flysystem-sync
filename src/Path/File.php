<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\FileAttributes;

use function array_diff;
use function trim;

class File
{
    public readonly string $path;

    public readonly string $type;

    public readonly bool $is_file;

    public readonly bool $is_directory;

    public function __construct(
        string $path,
        public readonly ?int $fileSize = null,
        public readonly ?string $visibility = null,
        public readonly ?int $lastModified = null,
        public readonly ?string $mimeType = null
    ) {
        $this->path = trim(trim($path), '/');

        $this->type         = 'file';
        $this->is_file      = true;
        $this->is_directory = false;
    }

    public static function fromAttributes(FileAttributes $attributes): static
    {
        return new static(
            $attributes->path(),
            $attributes->fileSize(),
            $attributes->visibility(),
            $attributes->lastModified(),
            $attributes->mimeType()
        );
    }

    public function toArray(): array
    {
        return [
            'path'         => $this->path,
            'type'         => $this->type,
            'fileSize'     => $this->fileSize,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'mimeType'     => $this->mimeType,
        ];
    }
}
