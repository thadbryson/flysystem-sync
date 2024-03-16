<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Exceptions\SourceNotFound;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Filesystems\Writer;
use TCB\FlysystemSync\Paths\Contracts\Path;

abstract class AbstractRunner
{
    public readonly Reader $reader;

    public readonly Writer $writer;

    public readonly string $path;

    public readonly Action $action;

    public readonly Path $source;

    public function __construct(FilesystemAdapter $reader, FilesystemAdapter $writer, Path $source)
    {
        $this->path = $source->path;

        $this->reader = new Reader($reader);
        $this->writer = new Writer($writer);

        $this->source = static::assertSource($source, $this->path);
        $this->action = Action::get($this->source, $this->getTarget());
    }

    public static function fromPath(FilesystemAdapter $reader, FilesystemAdapter $writer, string $path): static
    {
        $filesystem = new Reader($reader);

        // Get either File or Directory.
        // Then ->assertSource() will throw an Exception if it's not File or Directory.
        // Can't have a getSource() because return type can't be overriden.
        $source = $filesystem->getPath($path) ?? throw new SourceNotFound($path);
        $source = static::assertSource($source, $path);

        return new static($reader, $writer, $source);
    }

    public static function factory(
        FilesystemAdapter $reader,
        FilesystemAdapter $writer,
        Path $source
    ): FileRunner|DirectoryRunner {
        return $source->isFile() ?
            new FileRunner($reader, $writer, $source) :
            new DirectoryRunner($reader, $writer, $source);
    }

    abstract public static function assertSource(Path $source, string $path): Path;

    abstract protected function create(): void;

    abstract protected function update(): void;

    public function execute(): bool
    {
        match ($this->action) {
            Action::CREATE  => $this->create(),
            Action::UPDATE  => $this->update(),
            Action::NOTHING => null
        };
    }

    public function isSame(): bool
    {
        return $this->source->isChanged($this->getTarget()) === false;
    }

    protected function getTarget(): ?Path
    {
        return $this->writer->getPath($this->path);
    }
}
