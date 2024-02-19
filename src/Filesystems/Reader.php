<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;

class Reader implements FilesystemReader
{
    use Traits\ReadFunctions,
        Traits\Assertions;

    public function attributes(string $path): ?StorageAttributes
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
}
