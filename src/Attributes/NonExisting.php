<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Attributes;

use League\Flysystem\StorageAttributes;

class NonExisting implements StorageAttributes
{
    protected readonly string $path;

    protected readonly string $type;

    public function __construct(string $path, ?string $type)
    {
        $this->path = $path;
        $this->type = $type ?? StorageAttributes::TYPE_FILE;
    }

    public function offsetExists(mixed $offset): bool
    {
        return false;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function visibility(): ?string
    {
        return null;
    }

    public function lastModified(): ?int
    {
        return null;
    }

    public static function fromArray(array $attributes): StorageAttributes
    {
        return new static(
            $attributes['path'] ?? '',
            $attributes['type'] ?? ''
        );
    }

    public function isFile(): bool
    {
        return $this->type() === StorageAttributes::TYPE_FILE;
    }

    public function isDir(): bool
    {
        return $this->type() === StorageAttributes::TYPE_DIRECTORY;
    }

    public function withPath(string $path): StorageAttributes
    {
        return new static($path, $this->type());
    }

    public function extraMetadata(): array
    {
        return [];
    }

    public function jsonSerialize(): mixed
    {
        return null;
    }
}
