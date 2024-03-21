<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Traits;

use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Contracts\Path;
use TCB\FlysystemSync\Paths\Directory;
use TCB\FlysystemSync\Paths\File;

use function array_diff;

/**
 * @mixin Path
 */
trait PathTrait
{
    public function getDifferences(?Path $target): array
    {
        if ($target === null) {
            return $this->toArray();
        }

        // Types don't match.
        if ($this->isFile() !== $target->isFile() ||
            $this->isDirectory() !== $target->isDirectory()
        ) {
            return [
                'path' => $this->path,
                'type' => static::class,
            ];
        }

        $diffs = array_diff(
            $this->toArray(),
            $target->toArray()
        );

        unset($diffs['path']);
        unset($diffs['lastModified']);

        return $diffs;
    }

    public function isFile(): bool
    {
        return static::class === File::class;
    }

    public function isDirectory(): bool
    {
        return static::class === Directory::class;
    }
}
