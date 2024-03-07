<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\FileAttributes;

class File extends AbstractPath
{
    public readonly ?int $fileSize;

    public readonly ?string $mimeType;

    public function __construct(
        string $path,
        ?string $visibility = null,
        ?int $lastModified = null,
        ?int $fileSize = null,
        ?string $mimeType = null
    ) {
        parent::__construct($path, $visibility, $lastModified, true, false);

        $this->fileSize = $fileSize;
        $this->mimeType = $mimeType;
    }

    public static function fromFileAttributes(FileAttributes $attributes): File
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
        return [
            'path'         => $this->path,
            'type'         => $this->type,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'fileSize'     => $this->fileSize,
            'mimeType'     => $this->mimeType,
        ];
    }
}
