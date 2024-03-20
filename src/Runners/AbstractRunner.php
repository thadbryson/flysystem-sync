<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Action;
use TCB\FlysystemSync\Exceptions\SourceNotFound;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Filesystems\Writer;
use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Paths\Contracts\Path;
use Throwable;

abstract class AbstractRunner
{
    public readonly Reader $reader;

    public readonly Writer $writer;

    public readonly string $path;

    public readonly Path $source;

    public readonly Path $target;

    public readonly Action $action;

    public function __construct(Reader $reader, Writer $writer, Path $source)
    {
        $this->reader = $reader;
        $this->writer = $writer;

        $this->path = $source->path;

        $source       = $this->reader->getPath($this->path);
        $this->source = static::assertSource($source, $this->path);

        $this->loadTarget();
        $this->action = Action::get($this->source, $this->target);
    }

    public static function fromPath(Reader $reader, Writer $writer, string $path): static
    {
        // Get either File or Directory.
        // Then ->assertSource() will throw an Exception if it's not File or Directory.
        // Can't have a getSource() because return type can't be overriden.
        $source = $reader->getPath($path) ?? throw new SourceNotFound($path);
        $source = static::assertSource($source, $path);

        return new static($reader, $writer, $source);
    }

    public static function factory(Reader $reader, Writer $writer, Path $source): FileRunner|DirectoryRunner
    {
        return $source->isFile() ?
            new FileRunner($reader, $writer, $source) :
            new DirectoryRunner($reader, $writer, $source);
    }

    abstract public static function assertSource(Path $source, string $path): Path;

    abstract protected function create(): void;

    abstract protected function update(): void;

    public function execute(): Log
    {
        $log = new Log($this->path, static::class);

        try {
            $log->add('before', $this->source, $this->target);

            match ($this->action) {
                Action::CREATE  => $this->create(),
                Action::UPDATE  => $this->update(),
                Action::NOTHING => null
            };

            $log->add('after', $this->source, $this->target);
        }
        catch (Throwable $exception) {
            $log->addException($exception);
        }

        return $log;
    }

    public function getDifferences(): array
    {
        return $this->source->getDifferences($this->target);
    }

    public function loadTarget(): ?Path
    {
        $this->target = $this->writer->getPath($this->path);

        return $this->target;
    }
}
