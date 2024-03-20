<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use TCB\FlysystemSync\Paths\Contracts\Path;
use Throwable;

class Log
{
    public readonly string $path;

    public readonly string $runner;

    public array $items = [];

    public array $exceptions = [];

    public function __construct(string $path, string $runner)
    {
        $this->path   = $path;
        $this->runner = $runner;
    }

    public function add(string $key, Path $source, ?Path $target): static
    {
        $this->items[$key] = [
            'source'     => $source->toArray(),
            'target'     => $target->toArray(),
            'differencs' => $source->getDifferences($target),
        ];

        return $this;
    }

    public function get(string $key): string
    {
        return $this->items[$key] ?? '';
    }

    public function addException(Throwable $exception): static
    {
        $this->exceptions[] = $exception;

        return $this;
    }
}
