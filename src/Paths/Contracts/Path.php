<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contracts;

interface Path
{
    public function isFile(): bool;

    public function isDirectory(): bool;
}
