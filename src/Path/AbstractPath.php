<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use function TCB\FlysystemSync\Functions\path_prepare;

abstract class AbstractPath
{
    public readonly string $path;

    public readonly string $type;

    public readonly ?string $visibility;

    public readonly ?int $lastModified;

    public function __construct(
        string $path,
        ?string $visibility,
        ?int $lastModified,
    ) {
        $this->path = path_prepare($path);

        $this->visibility   = $visibility;
        $this->lastModified = $lastModified;

        $this->type = $this->isFile() ?
            'file' :
            'directory';
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

    public function toString(): string
    {
        return $this->path;
    }

    public function isFile(): bool
    {
        return $this instanceof File;
    }

    public function isDirectory(): bool
    {
        return $this instanceof Directory;
    }

    public function isDifferent(File|Directory $target): bool
    {
        return
            $this->isDifferentProperties($target) ||
            $this->isDifferentVisibility($target);
    }

    public function isDifferentProperties(File|Directory $target): bool
    {
        return
            $this->lastModified > $target->lastModified ||
            $this->type !== $target->type;
    }

    public function isDifferentVisibility(File|Directory $target): bool
    {
        return $this->visibility !== $target->visibility;
    }
}
