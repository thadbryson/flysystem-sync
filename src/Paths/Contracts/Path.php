<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contracts;

interface Path
{
    public const TYPE_FILE = 'file';

    public const TYPE_DIRECTORY = 'directory';

    public function toArray(): array;

    public function isFile(): bool;

    public function isDirectory(): bool;

    public function getDifferences(?Path $target): array;
}
