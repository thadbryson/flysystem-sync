<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Contract\Path;
use TCB\FlysystemSync\Paths\Type;

class Reader implements FilesystemReader
{
    use Traits\ReadFunctions;

    public function makePath(string $path): Path
    {
        if ($this->pathExists($path) === false) {
            return new Type\NullPath($path);
        }

        return $this->directoryExists($path) ?
            new Type\Directory($path) :
            new Type\File($path);
    }

    public function makeFile(string $path): Type\File
    {
        if ($this->fileExists($path) === true) {
            return new Type\File($path);
        }

        throw new \Exception;
    }

    public function getDirectoryContents(string $location): array
    {
        $contents = [
            $location => static::makeDirectory($location),
        ];

        $this->filesystem
            ->listContents($location, FilesystemReader::LIST_DEEP)
            ->map(function (StorageAttributes $current) use (&$contents): void {

                $contents[$current->path()] = $current instanceof DirectoryAttributes ?
                    new Type\Directory($current->path()) :
                    new Type\File($current->path());
            });

        return $contents;
    }

    public function makeDirectory(string $path): Type\Directory
    {
        if ($this->directoryExists($path) === true) {
            return new Type\Directory($path);
        }

        throw new \Exception;
    }
}
