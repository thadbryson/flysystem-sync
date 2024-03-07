<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function array_key_exists;
use function array_keys;
use function TCB\FlysystemSync\Functions\path_collect;
use function TCB\FlysystemSync\Functions\path_prepare;

class PathCollection
{
    /**
     * @var File[]|Directory[]|null[]
     */
    protected array $items = [];

    public readonly Reader $reader;

    public function __construct(FilesystemAdapter $reader)
    {
        $this->reader = new Reader($reader);
    }

    /**
     * Clone with another Filesystem and sort TARGETS.
     */
    public function loadNew(FilesystemAdapter $reader): static
    {
        $targets = new static($reader);
        $targets->set(null, ...$this->paths());

        return $targets;
    }

    public function paths(): array
    {
        return array_keys($this->items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function has(string $path): bool
    {
        $path = path_prepare($path);

        return array_key_exists($path, $this->items);
    }

    public function set(?bool $is_file, string ...$paths): void
    {
        foreach ($paths as $path) {
            // @todo log Exceptions
            $path = path_prepare($path);

            $found = match ($is_file) {
                true  => $this->reader->loadFile($path),
                false => $this->reader->loadDirectory($path),
                null  => $this->reader->load($path) ?? throw new \Exception('Not found')
            };

            path_collect($this->items, $found, $path);
        }
    }
}
