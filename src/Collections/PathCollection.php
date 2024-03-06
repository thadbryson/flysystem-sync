<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;
use Throwable;

use function array_keys;
use function TCB\FlysystemSync\Functions\Helper\path_collect;

class PathCollection
{
    /**
     * @var File[]|Directory[]|null[]
     */
    protected array $items = [];

    /**
     * @var Throwable[]
     */
    protected array $exceptions = [];

    protected readonly ReaderFilesystem $reader;

    public function __construct(FilesystemAdapter $reader)
    {
        $this->reader = new ReaderFilesystem($reader);
    }

    /**
     * Clone with another Filesystem.
     */
    public function factory(FilesystemAdapter $reader): static
    {
        $product = new static($reader);
        $product->path(...$this->paths());

        return $product;
    }

    /**
     * Clone with another Filesystem.
     */
    public function combine(self $targets): static
    {
        $sources = $this->all();
        $targets = $targets->all();
    }

    public function paths(): array
    {
        return array_keys($this->items);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function exceptions(): array
    {
        return $this->exceptions;
    }

    public function file(string ...$paths): static
    {
        return $this->set($paths, true);
    }

    public function directory(string ...$paths): static
    {
        return $this->set($paths, false);
    }

    public function path(string ...$paths): static
    {
        return $this->set($paths, null);
    }

    protected function set(array $paths, ?bool $is_file): static
    {
        foreach ($paths as $path) {
            try {
                $found = match ($is_file) {
                    true  => $this->reader->loadFile($path),
                    false => $this->reader->loadDirectory($path),
                    null  => $this->reader->load($path) ?? throw new \Exception('Not found')
                };

                path_collect($this->items, $path, $found);
            }
            catch (Throwable $exception) {
                $this->exceptions[$path] = $exception;
            }
        }

        return $this;
    }
}
