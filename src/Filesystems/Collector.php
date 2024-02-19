<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystems;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Enums\PathTypes;

class Collector
{
    protected array $items = [];

    public readonly Reader $reader;

    public function __construct(FilesystemReader $reader)
    {
        $this->reader = new Reader($reader);
    }

    public function clone(FilesystemReader $clone): static
    {
        $clone = new static($clone);
        $clone->collect(...$this->paths());

        return $clone;
    }

    /**
     * @return FileAttributes[]|DirectoryAttributes[]|null[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Return all string paths collected.
     *
     * @return string[]
     */
    public function paths(): array
    {
        return array_keys($this->items);
    }

    public function collect(string ...$paths): static
    {
        return $this->collectInternal(PathTypes::NON_EXISTING, $paths);
    }

    public function addFiles(string ...$files): static
    {
        return $this->collectInternal(PathTypes::FILE, $files);
    }

    public function addDirectories(string ...$directories): static
    {
        return $this->collectInternal(PathTypes::DIRECTORY, $directories);
    }

    protected function collectInternal(?PathTypes $assertType, array $paths): static
    {
        foreach ($paths as $path) {
            /** @var FileAttributes|DirectoryAttributes|null $attributes */
            $attributes = $this->reader->attributes($path);

            // Assert file/directory type are correct.
            $type = $assertType === null ?
                PathTypes::match($attributes) :
                PathTypes::assert($assertType, $attributes);

            match ($type) {
                PathTypes::NON_EXISTING => $this->items[$path] = null,
                PathTypes::FILE         => $this->items[$attributes->path()] = $attributes,

                // Lists all paths in DOT notation.
                // Ex: [
                //     '/home/thad/apps/browser',
                //     '/home/thad/docs/license.pdf',
                // ]
                //
                // No reason to do recursion here.
                PathTypes::DIRECTORY    => $this
                    ->reader->listContents(
                        $attributes->path(),
                        FilesystemReader::LIST_DEEP
                    )
                    ->map(
                        fn (StorageAttributes $content) => $this->items[$content->path()] = $content
                    )
            };
        }

        return $this;
    }
}
