<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Helpers\Loader;

class Sync
{
    public Contracts\Runner $runner;

    private function __construct()
    {
    }

    public static function make(FilesystemAdapter $reader, FilesystemAdapter $writer): static
    {
        $sync         = new static;
        $sync->runner = new Runner($reader, $writer);

        return $sync;
    }

    public static function fromRunner(Contracts\Runner $runner): static
    {
        $sync         = new static;
        $sync->runner = $runner;

        return $sync;
    }

    public function file(string $path): void
    {
        $source = Loader::getFile($this->runner->reader, $path) ?? throw new \Exception('FILE not found');
        $target = Loader::getPath($this->runner->writer, $path);

        $this->runner->execute($source, $target);
    }

    public function directory(string $path): void
    {
        $results = [];

        $this->runner->reader
            ->listContents($path, true)
            ->map(function (StorageAttributes $source) use (&$results): bool {
                $target = Loader::getPath($this->runner->writer, $source->path());

                $results[$source->path()] = $this->runner->execute($source, $target);
            });
    }
}
