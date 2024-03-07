<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use function array_diff;
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

    abstract public function toArray(): array;

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
        return $this->getDifferences($target) !== [];
    }

    public function getDifferences(File|Directory $target): array
    {
        $differences = array_diff(
            $this->toArray(),
            $target->toArray()
        );

        // Can't edit last modified
        if ($this->lastModified <= $target->lastModified) {
            unset($differences['lastModified']);
        }

        return $differences;
    }
}
