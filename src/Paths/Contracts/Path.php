<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contracts;

interface Path
{
    public function toArray(): array;

    public function isFile(): bool;

    public function isDirectory(): bool;

    public function clone(): static;

    public function isSame(?Path $target): bool;

    public function getDifferences(?Path $target): array;
}
