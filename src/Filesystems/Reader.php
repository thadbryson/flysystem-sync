<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Type;

class Reader
{
    use Traits\ReadFunctions,
        Traits\MakeFunctions;

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
}
