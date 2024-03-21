<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contracts;

/**
 * @property-read string      $path      ,
 * @property-read bool        $exists    ,
 * @property-read string|null $visibility,
 * @property-read int|null    $lastModified
 */
interface Path
{
    public function toArray(): array;

    public function isFile(): bool;

    public function isDirectory(): bool;

    public function getDifferences(?Path $target): array;

    public function withPath(string $path): static;
}
