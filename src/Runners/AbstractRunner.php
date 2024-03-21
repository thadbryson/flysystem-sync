<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runners;

use TCB\FlysystemSync\Exceptions\SourceNotFound;
use TCB\FlysystemSync\Filesystems\Reader;
use TCB\FlysystemSync\Filesystems\Writer;
use TCB\FlysystemSync\Helpers\ActionEnum;
use TCB\FlysystemSync\Log;
use TCB\FlysystemSync\Paths\Contracts\Path;
use Throwable;

abstract class AbstractRunner
{
    public readonly Log $log;

    public function __construct(
        public readonly Reader $reader,
        public readonly Writer $writer,
        public readonly Path $source,
        public readonly Path $target
    ) {
        static::assertSource($source);

        $this->log = new Log($this->source, $this->target, static::class);
    }

    public static function fromPath(
        Reader $reader,
        Writer $writer,
        string $source,
        ?string $target
    ): static {
        $target = $target ?? $source;

        // Get either File or Directory.
        // Then ->assertSource() will throw an Exception if it's not File or Directory.
        // Can't have a getSource() because return type can't be overriden.
        $source = $reader->getPath($source) ?? throw new SourceNotFound($source);
        $target = $writer->getPath($target) ?? $source->withPath($target);

        return new static($reader, $writer, $source, $target);
    }

    abstract protected static function assertSource(Path $source): Path;

    abstract protected function create(): void;

    abstract protected function update(): void;

    public function execute(): Log
    {
        try {
            match ($this->log->action) {
                ActionEnum::CREATE  => $this->create(),
                ActionEnum::UPDATE  => $this->update(),
                ActionEnum::NOTHING => null
            };

            $this->log->add(Log::STAGE_AFTER, $this->source, $this->loadTarget());
        }
        catch (Throwable $exception) {
            $this->log->exception = $exception;
        }

        return $this->log;
    }

    public function getDifferences(): array
    {
        return $this->source->getDifferences($this->target);
    }

    public function loadTarget(): ?Path
    {
        return $this->writer->getPath($this->target->path);
    }
}
