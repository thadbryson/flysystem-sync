<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\FileAttributes;

class File extends AbstractPath
{
    public function __construct(
        string $path,
        ?string $visibility,
        ?int $lastModified,
        public readonly ?int $fileSize,
        public readonly ?string $mimeType
    ) {
        parent::__construct($path, $visibility, $lastModified);
    }

    public static function fromAttributes(FileAttributes $attributes): static
    {
        return new static(
            $attributes->path(),
            $attributes->visibility(),
            $attributes->lastModified(),
            $attributes->fileSize(),
            $attributes->mimeType()
        );
    }

    public function toArray(): array
    {
        $export = parent::toArray();

        $export['file_size'] = $this->fileSize;
        $export['mime_type'] = $this->mimeType;

        return $export;
    }
}
