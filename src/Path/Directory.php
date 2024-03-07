<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use League\Flysystem\DirectoryAttributes;

class Directory extends AbstractPath
{
    public function __construct(
        string $path,
        ?string $visibility = null,
        ?int $lastModified = null
    ) {
        parent::__construct($path, $visibility, $lastModified, false, true);
    }

    public static function fromDirectoryAttributes(DirectoryAttributes $attributes): Directory
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
