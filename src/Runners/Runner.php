<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Filesystems\Writer;
use TCB\FlysystemSync\Helpers\PathHelper;
use TCB\FlysystemSync\Paths\Contracts\Path;

abstract class Runner
{
    public readonly Reader $reader;

    public readonly Writer $writer;

    public readonly string $path;

    public readonly Action $action;

    public readonly Path $source;

    public function __construct(FilesystemAdapter $reader, FilesystemAdapter $writer, string $path)
    {
        $this->path = PathHelper::prepare($path);

        $this->reader = new Reader($reader);
        $this->writer = new Writer($writer);

        $source = $this->reader->getPath($this->path);

        $this->source = $this->assertSource($source, $this->path);
        $this->action = Action::get($this->source, $this->getTarget());
    }

    abstract protected function assertSource(?Path $source, string $path): Path;

    abstract protected function create(): void;

    abstract protected function update(): void;

    public function execute(): bool
    {
        match ($this->action) {
            Action::CREATE  => $this->create(),
            Action::UPDATE  => $this->update(),
            Action::NOTHING => null
        };

        return $this->equals();
    }

    protected function getTarget(): ?Path
    {
        return $this->writer->getPath($this->path);
    }

    protected function equals(): bool
    {
        return $this->source->isSame($this->getTarget());
    }
}
