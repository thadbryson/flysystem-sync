<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Traits;

use TCB\FlysystemSync\Paths\Contract;

trait Path
{
    protected readonly string $path;

    public function __construct(string $path)
    {
        $this->path = trim($path);
    }

    public function isSame(Contract\Path $path): bool
    {
        return
            $this->path === $path->path() &&
            $this->isFile() === $path->isFile() &&
            $this->isDirectory() === $path->isDirectory();
    }

    public function path(): string
    {
        return $this->path;
    }

    abstract public function isFile(): bool;

    abstract public function isDirectory(): bool;
}
