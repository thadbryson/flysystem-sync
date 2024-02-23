<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Helpers\ArrayKeyCompare;
use TCB\FlysystemSync\Paths\Contracts\Path;

use function array_keys;

class Runner
{
    public readonly FileRunner $file_runner;

    public readonly DirectoryRunner $directory_runner;

    protected readonly Filesystems\Extended $reader;

    protected readonly Filesystems\Extended $writer;

    /**
     * @var Path[]
     */
    public readonly array $creates;

    /**
     * @var Path[]
     */
    public readonly array $updates;

    /**
     * @var Path[]
     */
    public readonly array $deletes;

    public function __construct(
        FilesystemAdapter $reader,
        FilesystemAdapter $writer,
        array $files,
        array $directories
    ) {
        $this->reader = new Filesystems\Extended($reader);
        $this->writer = new Filesystems\Extended($writer);

        $all = array_merge($files, $directories);

        $sources = $this->reader->loadOrFail(...$all);                  // Must exist
        $targets = $this->writer->load(...array_keys($sources));        // Does not have to exist

        $hydrator = new ArrayKeyCompare($sources, $targets);

        $this->creates = $hydrator->onFirstOnly();
        $this->deletes = $hydrator->onSecondOnly();
        $this->updates = $hydrator->onBothWhen(fn (Path $source, Path $target) => $source->isEqual($target));

        $this->file_runner      = new FileRunner($this->reader, $this->writer);
        $this->directory_runner = new DirectoryRunner($this->reader, $this->writer);
    }

    public function runCreates(): void
    {
        foreach ($this->creates as $current) {
            $current->isFile() ?
                $this->file_runner->create($current->path) :
                $this->directory_runner->create($current->path);
        }
    }

    public function runUpdates(): void
    {
        foreach ($this->updates as $current) {
            $current->isFile() ?
                $this->file_runner->update($current->path) :
                $this->directory_runner->update($current->path);
        }
    }

    public function runDeletes(): void
    {
        foreach ($this->deletes as $current) {
            $current->isFile() ?
                $this->file_runner->delete($current->path) :
                $this->directory_runner->delete($current->path);
        }
    }
}
