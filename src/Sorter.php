<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\StorageAttributes;

/**
 * Helper class for the Sync class.
 * Gets filepaths that need writing, updating, and deleting.
 */
class Sorter
{
    protected readonly Factory $factory;

    public readonly array $create_files;

    public readonly array $delete_files;

    public readonly array $update_files;

    public readonly array $nothing_files;

    public readonly array $create_directories;

    public readonly array $delete_directories;

    public readonly array $update_directories;

    public readonly array $nothing_directories;

    public function __construct(Factory $factory, Collection $sources, Collection $targets)
    {
        $this->factory = $factory;

        $sources = $sources->all();
        $targets = $targets->all();

        $all = [];

        foreach ($sources as $source) {
            $target = $targets[$source->path()] ?? null;

            $this->set($all, $source, $target);
        }

        $this->create_files  = $all['create_files'];
        $this->delete_files  = $all['delete_files'];
        $this->update_files  = $all['update_files'];
        $this->nothing_files = $all['nothing_files'];

        $this->create_directories  = $all['create_directories'];
        $this->delete_directories  = $all['delete_directories'];
        $this->update_directories  = $all['update_directories'];
        $this->nothing_directories = $all['nothing_directories'];
    }

    protected function set(array &$actions, ?StorageAttributes $source, ?StorageAttributes $target): static
    {
        $bag  = Helper::getBag($source, $target);
        $path = ($source ?? $target)->path();

        // Bag doesn't exist?
        if (isset($actions[$bag]) === false) {
            throw new \Exception('No bag: ' . $bag);
        }

        // Path already set?
        if (isset($actions[$bag][$path])) {
            throw new \Exception('already has in: ' . $bag);
        }

        $actions[$bag][$path] = $this->factory->make($bag, $source, $target);

        return $this;
    }
}
