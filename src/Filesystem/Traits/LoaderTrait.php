<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem\Traits;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function TCB\FlysystemSync\Functions\path_collect;
use function TCB\FlysystemSync\Functions\path_prepare_many;

/**
 * @mixin FilesystemReader
 */
trait LoaderTrait
{
    public function loadFile(string $path): File
    {
        $file = $this->load($path) ?? throw new \Exception('FILE not found');

        if ($file instanceof File === false) {
            throw new \Exception('Not a FILE');
        }

        return $file;
    }

    public function loadDirectory(string $path): Directory
    {
        $directory = $this->load($path) ?? throw new \Exception('DIRECTORY not found');

        if ($directory instanceof Directory === false) {
            throw new \Exception('Not a DIRECTORY');
        }

        return $directory;
    }

    public function load(string $path): File|Directory|null
    {
        return match (true) {
            $this->fileExists($path)      => new File(
                $path,
                $this->visibility($path),
                $this->lastModified($path),
                $this->fileSize($path),
                $this->mimeType($path),
            ),

            $this->directoryExists($path) => new Directory(
                $path,
                $this->visibility($path),
                $this->lastModified($path)
            ),

            default                       => null
        };
    }

    /**
     * @return File[]|Directory[]|null[]
     */
    public function loadDeep(string ...$paths): array
    {
        $all   = [];
        $paths = path_prepare_many(...$paths);

        foreach ($paths as $current) {
            $current = path_collect($all, $this->load($current));

            // Get DIRECTORY contents?
            if ($current === null || $current->isFile()) {
                continue;
            }

            $this
                ->listContents($current->path, FilesystemReader::LIST_DEEP)
                ->sortByPath()
                ->filter(function (FileAttributes|DirectoryAttributes $attributes) use (&$all) {
                    path_collect(
                        $all,
                        $attributes->isFile() ?
                            File::fromAttributes($attributes) :
                            Directory::fromAttributes($attributes)
                    );
                });
        }

        return $all;
    }

    public function isSameLoaded(File|Directory $target): bool
    {
        $loaded = $this->load($target->path);

        if ($loaded === null) {
            return false;
        }

        return $loaded->isSame($target);
    }
}
