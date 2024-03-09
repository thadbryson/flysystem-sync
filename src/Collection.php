<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;

use function array_key_exists;
use function array_keys;

class Collection
{
    /**
     * @var ?StorageAttributes[]
     */
    protected array $items = [];

    public function __construct(
        public Filesystem $reader,
        string ...$paths
    ) {
        $this->load(...$paths);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function paths(): array
    {
        return array_keys($this->items);
    }

    public function has(string $path): bool
    {
        $path = Helper::preparePath($path);

        return array_key_exists($path, $this->items);
    }

    protected function loadInternal(bool $only_new, array $paths): static
    {
        foreach ($paths as $path) {
            if ($only_new === false || $this->has($path) === false) {
                $this->find($path);
            }
        }

        return $this;
    }

    public function load(string ...$paths): static
    {
        return $this->loadInternal(false, $paths);
    }

    public function loadNew(string ...$paths): static
    {
        return $this->loadInternal(true, $paths);
    }

    public function file(string $path): static
    {
        return $this->set(
            Helper::getFile($this->reader, $path)
        );
    }

    public function directory(string $path): static
    {
        return $this->set(
            Helper::getDirectory($this->reader, $path)
        );
    }

    public function find(string $path): static
    {
        return $this->set(
            Helper::getPath($this->reader, $path),
        );
    }

    protected function set(?StorageAttributes $found): static
    {
        if ($found === null) {
            return $this;
        }

        $path = Helper::preparePath($found->path());

        $this->items[$path] = $found;

        if ($found instanceof DirectoryAttributes) {
            $this->reader
                ->listContents($path, FilesystemReader::LIST_DEEP)
                ->map(function (StorageAttributes $listing): void {
                    $path = Helper::preparePath($listing->path());

                    $this->items[$path] = $listing;
                });
        }

        return $this;
    }
}
