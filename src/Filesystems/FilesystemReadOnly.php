<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Exceptions;

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

    public function hydrate(string ...$paths): array
    {
        $found = [];

        foreach ($paths as $path) {
            $current = $this->attributes($path);

            if ($current instanceof FileAttributes) {
                $found[$path] = $current;
            }
            elseif ($current instanceof DirectoryAttributes) {
                $listing = $this->listContents($path, FilesystemReader::LIST_DEEP);

                foreach ($listing as $deep) {
                    $found[$deep->path()] = $deep;
                }
            }
            else {
                throw new \Exception('invalid path: ' . $path);
            }
        }

        return $found;
    }
}
