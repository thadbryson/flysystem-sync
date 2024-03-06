<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem\Traits;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use TCB\FlysystemSync\Path\AbstractPath;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function TCB\FlysystemSync\Functions\Helper\path_collect;
use function TCB\FlysystemSync\Functions\Helper\path_prepare_many;

/**
 * @mixin Filesystem
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
                true,
                $this->visibility($path),
                $this->lastModified($path),
                $this->fileSize($path),
                $this->mimeType($path),
            ),

            $this->directoryExists($path) => new Directory(
                $path,
                true,
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

        foreach ($paths as $current_path) {
            path_collect($all, $current_path, $this->load($current_path));

            // Get DIRECTORY contents?
            if ($all[$current_path] !== null && $all[$current_path]->is_directory) {
                $contents = $this->listContents($current_path, FilesystemReader::LIST_DEEP);

                // Iterate through DIRECTORY contents, key by PATH.
                // Cannot use ->map()->toArray() because it doesn't set PATH keys.
                foreach ($contents as $current_content) {
                    $content_current = AbstractPath::fromAttributes($current_content, true);

                    path_collect($all, $current_content->path, $content_current);
                }
            }
        }

        return $all;
    }
}
