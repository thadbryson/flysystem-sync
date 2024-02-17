<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;

readonly class FilesystemReadOnly implements FilesystemReader
{
    use FilesystemReaderTrait;

    public function attributes(string $path): StorageAttributes|null
    {
        if ($this->fileExists($path)) {
            return new FileAttributes(
                $path,
                $this->fileSize($path),
                $this->visibility($path),
                $this->lastModified($path),
                $this->mimeType($path)
            );
        }
        elseif ($this->directoryExists($path)) {
            return new DirectoryAttributes(
                $path,
                $this->visibility($path),
                $this->lastModified($path),
            );
        }

        return null;
    }

    public function assertHas(string $path): StorageAttributes
    {
        return $this->attributes($path) ?? throw new Exceptions\PathNotFound($path);
    }
}
