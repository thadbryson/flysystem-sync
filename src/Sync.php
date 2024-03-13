<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Exceptions\DirectoryNotFound;
use TCB\FlysystemSync\Exceptions\FileNotFound;
use TCB\FlysystemSync\Helpers\Loader;

use function array_keys;
use function array_map;
use function array_merge;

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
        $source = Loader::getFile($this->runner->reader, $path) ?? throw new FileNotFound($path);
        $target = Loader::getPath($this->runner->writer, $path);

        $this->runner->execute($source, $target);
    }

    public function directory(string $path): void
    {
        $sources = Loader::getDirectoryContents($this->runner->reader, $path) ?? throw new DirectoryNotFound($path);
        $targets = Loader::getDirectoryContents($this->writer->reader, $path) ?? [];

        $paths_all = array_merge(
            array_keys($sources),
            array_keys($targets)
        );

        $results = array_map(function (string $path): array {
            $source = $sources[$path] ?? null;
            $target = $targets[$path] ?? null;

            return $this->runner->execute($source, $target);
        }, $paths_all);

        $results = array_map(function (array $current): array {
            $current['execute_final'] = match ($current['action']) {
                Action::CREATE_FILE       => $this->runner->sameFiles($current['source']),
                Action::DELETE_FILE       => $this->runner->fileExistsNot($current['target']),
                Action::UPDATE_FILE       => $this->runner->sameFiles($current['source']),
                Action::NOTHING_FILE      => $this->runner->sameFiles($current['source']),

                Action::CREATE_DIRECTORY  => $this->runner->sameDirectories($current['source']),
                Action::DELETE_DIRECTORY  => $this->runner->directoryExistsNot($current['target']),
                Action::UPDATE_DIRECTORY  => $this->runner->sameDirectories($current['source']),
                Action::NOTHING_DIRECTORY => $this->runner->sameDirectories($current['source'])
            };

            return $current;
        }, $paths_all);
    }

    public function directoryWithoutContents(string $path): void
    {
        $source = Loader::getDirectory($this->runner->reader, $path) ?? throw new DirectoryNotFound($path);
        $target = Loader::getPath($this->runner->writer, $path);

        $this->runner->execute($source, $target);
    }
}
