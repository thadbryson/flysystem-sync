<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\StorageAttributes;

class Directory extends AbstractPath
{
    public function __construct(
        string $path,
        ?bool $exists = false,
        ?string $visibility = null,
        ?int $lastModified = null
    ) {
        parent::__construct($path, $exists, $visibility, $lastModified, false, true);
    }

    public static function fromAttributes(StorageAttributes $attributes, ?bool $exists = null): Directory
    {
        if ($attributes instanceof DirectoryAttributes === false) {
            throw new \Exception('');
        }

        return new static(
            $attributes->path(),
            $exists,
            $attributes->visibility(),
            $attributes->lastModified()
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
        ];
    }
}
