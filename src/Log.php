<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use Throwable;

class Log
{
    public array $items = [];

    public array $exceptions = [];

    public function add(string $key, mixed $value): static
    {
        $this->items[$key] = $value;

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
