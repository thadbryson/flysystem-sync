<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Traits;

use TCB\FlysystemSync\Paths\Contracts\Path;

use function array_diff_key;

/**
 * @mixin Path
 */
trait CompareTrait
{
    /**
     * Should these paths be updated?
     */
    public function isSame(?Path $target): bool
    {
        return $this->getDifferences($target) === [];
    }

    public function getDifferences(?Path $target): array
    {
        if ($target === null) {
            return $this->toArray();
        }

        return array_diff_key(
            $this->toArray(),
            $target->toArray()
        );
    }
}
