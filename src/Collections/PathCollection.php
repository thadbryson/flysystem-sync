<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Filesystems\FilesystemReadOnly;

class PathCollection
{
    /**
     * @var StorageAttributes[]
     */
    protected array $items = [];

    public function add(StorageAttributes $source): void
    {
        $this->items[$source->path()] = $source;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function hydrate(FilesystemReader $filesystem): array
    {
        $filesystem = new FilesystemReadOnly($filesystem);
        $hydrated   = [];

        foreach ($this->all() as $path => $source) {
            if ($source instanceof FileAttributes) {
                $hydrated[$path] = $source;
            }
            elseif ($source instanceof DirectoryAttributes) {
                $listing = $filesystem->listContents($path, FilesystemReader::LIST_DEEP);

                foreach ($listing as $found) {
                    $hydrated[$found->path()] = $found;
                }
            }
            else {
                throw new \Exception('invalid path: ' . $path);
            }
        }

        return $hydrated;
    }
}
