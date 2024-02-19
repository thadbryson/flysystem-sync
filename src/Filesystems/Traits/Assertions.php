<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems\Traits;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Enums\PathTypes;
use TCB\FlysystemSync\Exceptions;

/**
 * @mixin FilesystemOperator
 */
trait Assertions
{
    use ReadFunctions;

    public function assertHas(string $path): StorageAttributes
    {
        return $this->attributes($path) ?? throw new Exceptions\PathNotFound($path);
    }

    public function assertHasNot(string $path): null
    {
        if ($this->has($path)) {
            throw new \Exception('Path should not exist: ' . $path);
        }

        return null;
    }

    public function assertType(PathTypes $type, string $path): StorageAttributes
    {
        return match ($type) {
            PathTypes::NON_EXISTING => $this->assertHasNot($path),
            PathTypes::DIRECTORY    => $this->assertDirectory($path),
            PathTypes::FILE         => $this->assertFile($path)
        };
    }

    public function assertPath(string $path): StorageAttributes
    {
        return $this->attributes($path) ?? throw new \Exception('Path not found: ' . $path);
    }

    public function assertDirectory(string $path): DirectoryAttributes
    {
        /** @var DirectoryAttributes $found */
        $found = $this->assertType(PathTypes::DIRECTORY, $path);

        return $found;
    }

    public function assertFile(string $path): FileAttributes
    {
        /** @var FileAttributes $found */
        $found = $this->assertType(PathTypes::FILE, $path);

        return $found;
    }
}
