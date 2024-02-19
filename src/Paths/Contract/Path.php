<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contract;

interface Path
{
    public function path(): string;

    public function isSame(Path $path): bool;

    public function isFile(): bool;

    public function isDirectory(): bool;
}
