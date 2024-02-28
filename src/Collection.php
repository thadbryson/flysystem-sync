<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync;

use function in_array;

class Collection
{
    /**
     * @var string[]
     */
    protected array $items = [];

    public function add(string $path): static
    {
        $path = Filesystem\Helper::preparePath($path);

        // Make sure path isn't already added.
        if ($this->has($path)) {
            throw new \Exception;
        }

        $this->items[] = $path;

        return $this;
    }

    public function has(string $path): bool
    {
        $path = Filesystem\Helper::preparePath($path);

        return in_array($path, $this->items, true);
    }

    public function all(): array
    {
        return $this->items;
    }
}
