<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\FilesystemReader;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

class Loader
{
    public static function loadPath(FilesystemReader $reader, string $path): File|Directory|null
    {
        $path = HelperFilesystem::preparePath($path);

        // a file?
        if ($reader->fileExists($path)) {
            return new File(
                $path,
                $reader->fileSize($path),
                $reader->visibility($path),
                $reader->lastModified($path),
                $reader->mimeType($path),
            );
        }
        // a directory?
        elseif ($reader->directoryExists($path)) {
            return new Directory(
                $path,
                $reader->visibility($path),
                $reader->lastModified($path)
            );
        }

        // Not found on filesystem
        return null;
    }

    /**
     * @return File[]|Directory[]
     */
    public static function loadPathsDeep(FilesystemReader $reader, array $paths): array
    {
        $all = [];

        foreach ($paths as $path) {
            $path = HelperFilesystem::preparePath($path);

            $all[$path] = static::loadPath($reader, $path);

            if ($all[$path]->is_directory) {
                $reader
                    ->listContents($path, FilesystemReader::LIST_DEEP)
                    ->sortByPath()
                    ->filter(function (File|Directory $content) use (&$all) {
                        $path_current = HelperFilesystem::preparePath($content->path);

                        $all[$path_current] = $content;
                    });
            }
        }

        return $all;
    }
}
