<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use function array_diff_key;
use function array_filter;
use function array_intersect_key;

readonly class ArrayKeyCompare
{
    public array $first;

    public array $second;

    public function __construct(array $first, array $second)
    {
        $this->first  = $first;
        $this->second = $second;
    }

    public function onFirstOnly(): array
    {
        // On first, leaves first
        return array_diff_key($this->first, $this->second);
    }

    public function onSecondOnly(): array
    {
        // On first, leaves first
        return array_diff_key($this->second, $this->first);
    }

    public function onBoth(): array
    {
        // On BOTH but different, leaves first
        return array_intersect_key($this->first, $this->second);
    }

    public function onBothWhen(callable $comparison): array
    {
        // On BOTH but different, leaves first

        // We only want pairs with different properties.
        return array_filter(

            $this->onBoth(),

            fn (mixed $source, int|string $key): bool => true === $comparison(
                    $key,
                    $source,
                    $this->second[$source->path] ?? null
                )
        );
    }
}
