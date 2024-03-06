<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

class File extends AbstractPath
{
    public readonly ?int $fileSize;

    public readonly ?string $mimeType;

    public function __construct(
        string $path,
        ?bool $exists = false,
        ?string $visibility = null,
        ?int $lastModified = null,
        ?int $fileSize = null,
        ?string $mimeType = null
    ) {
        parent::__construct($path, $exists, $visibility, $lastModified, true, false);

        $this->fileSize = $fileSize;
        $this->mimeType = $mimeType;
    }

    public static function fromAttributes(StorageAttributes $attributes, ?bool $exists = null): File
    {
        if ($attributes instanceof FileAttributes === false) {
            throw new \Exception('');
        }

        return new static(
            $attributes->path(),
            $exists,
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
            'type'         => $this->type,
            'visibility'   => $this->visibility,
            'lastModified' => $this->lastModified,
            'fileSize'     => $this->fileSize,
            'mimeType'     => $this->mimeType,
        ];
    }
}
