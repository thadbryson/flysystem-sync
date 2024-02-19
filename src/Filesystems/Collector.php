<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\FilesystemReader;
use TCB\FlysystemSync\Paths\Contract\Path;
use TCB\FlysystemSync\Paths\Type\Directory;
use TCB\FlysystemSync\Paths\Type\File;
use function array_merge;

class Collector
{
    public readonly Reader $reader;

    /**
     * @var Path[]
     */
    protected array $items = [];

    public function __construct(FilesystemReader $reader)
    {
        $this->reader = new Reader($reader);
    }

    public function clone(FilesystemReader $reader): static
    {
        $collector = new static($reader);

        foreach ($this->all() as $path) {
            $collector->path($path->path());
        }

        return $collector;
    }

    /**
     * @return Directory[]|File[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public function path(string $path): static
    {
        $this->items[$path] = $this->reader->makePath($path);

        return $this;
    }

    public function file(string $file): static
    {
        $this->items[$file] = $this->reader->makeFile($file);

        return $this;
    }

    public function directory(string $directory): static
    {
        // Lists all paths in DOT notation.
        // Ex: [
        //     '/home/thad/apps/browser',
        //     '/home/thad/docs/license.pdf',
        // ]
        //
        // No reason to do recursion here.
        $this->items = array_merge(
            $this->items,
            $this->reader->getDirectoryContents($directory)
        );

        return $this;
    }
}
