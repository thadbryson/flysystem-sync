<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths;

use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

class Factory
{
    public static function make(StorageAttributes $attributes): File|Directory
    {
        return $attributes instanceof FileAttributes ?
            new File(
                $attributes->path(),
                $attributes->visibility(),
                $attributes->lastModified(),
                $attributes->fileSize(),
                $attributes->mimeType()
            )

            :

            new Directory(
                $attributes->path(),
                $attributes->visibility(),
                $attributes->lastModified()
            );
    }
}
