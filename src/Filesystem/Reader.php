<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemReader;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function TCB\FlysystemSync\Functions\path_collect;
use function TCB\FlysystemSync\Functions\path_prepare_many;

/**
 * Only Filesystem reading functions.
 *
 * No write functionality.
 */
class Reader
{
    /**
     * Performs Filesystem operations.
     */
    protected readonly Filesystem $filesystem;

    public function __construct(FilesystemAdapter $adapter)
    {
        $this->filesystem = new Filesystem($adapter);
    }

    public function exists(File|Directory $path): bool
    {
        return $path->isFile() ?
            $this->filesystem->fileExists($path->path) :
            $this->filesystem->directoryExists($path->path);
    }

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
            $this->filesystem->fileExists($path)      => new File(
                $path,
                $this->filesystem->visibility($path),
                $this->filesystem->lastModified($path),
                $this->filesystem->fileSize($path),
                $this->filesystem->mimeType($path),
            ),

            $this->filesystem->directoryExists($path) => new Directory(
                $path,
                $this->filesystem->visibility($path),
                $this->filesystem->lastModified($path)
            ),

            default                                   => null
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
                ->filesystem->listContents($current->path, FilesystemReader::LIST_DEEP)
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

    /**
     * @return resource
     * @throws \League\Flysystem\FilesystemException
     */
    public function getContents(File|Directory $source): mixed
    {
        return $this->filesystem->readStream($source->path);
    }
}
