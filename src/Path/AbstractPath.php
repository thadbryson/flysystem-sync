<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Path;

use function array_diff_key;
use function array_key_exists;
use function TCB\FlysystemSync\Functions\path_prepare;

abstract class AbstractPath
{
    public readonly string $path;

    public function __construct(
        string $path,
        public readonly ?string $visibility,
        public readonly ?int $lastModified
    ) {
        $this->path = path_prepare($path);
    }

    public function toArray(): array
    {
        return [
            'path'          => $this->path,
            'type'          => static::class,
            'is_file'       => $this->isFile(),
            'is_directory'  => $this->isDirectory(),
            'visibility'    => $this->visibility,
            'last_modified' => $this->lastModified,
        ];
    }

    public function isFile(): bool
    {
        return $this instanceof File;
    }

    public function isDirectory(): bool
    {
        return $this instanceof Directory;
    }

    public function isSame(File|Directory $target): bool
    {
        return $this->toArray() === $target->toArray();
    }

    public function isDifferent(File|Directory $target): bool
    {
        return !$this->isSame($target);
    }

    public function isDifferentOnlyVisibility(File|Directory $target): bool
    {
        $diff = array_diff_key(
            $this->toArray(),
            $target->toArray()
        );

        return count($diff) === 1 && array_key_exists('visibility', $diff);
    }
}
