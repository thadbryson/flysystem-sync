<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use function array_diff_key;
use function array_intersect_key;
use function array_key_exists;

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

    public function onBothWhen(callable $comparison): array
    {
        $both = [];

        // On BOTH but different, leaves first
        foreach ($this->onBoth() as $key => $first) {
            // Must use array_key_exists, value could be NULL and isset() or ??  null wouldn't work.
            if (array_key_exists($key, $this->second) === false) {
                continue;
            }

            $second = $this->second[$key];

            if ($comparison($key, $first, $second) === true) {
                $both[$key] = $first;
            }
        }

        return $both;
    }

    public function onBoth(): array
    {
        // On BOTH but different, leaves first
        return array_intersect_key($this->first, $this->second);
    }
}
