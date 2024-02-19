<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Paths\Contract;
use function array_diff_key;
use function array_filter;
use function array_intersect_key;

readonly class Hydrator
{
    /**
     * @var StorageAttributes[]
     */
    public array $deletes;

    /**
     * @var StorageAttributes[]
     */
    public array $creates;

    /**
     * @var StorageAttributes[]
     */
    public array $updates;

    /**
     * @param Contract\Path[] $reader - path string key
     * @param Contract\Path[] $writer - path string key
     *
     * @throws \Exception
     */
    public function __construct(array $reader, array $writer)
    {
        // On SOURCES only, leaves SOURCES
        $creates = array_diff_key($reader, $writer);

        // On TARGETS only, leaves TARGETS
        $deletes = array_diff_key($writer, $reader);

        // On BOTH and different, leaves SOURCES
        // We only want pairs with different properties.
        $updates = array_intersect_key($reader, $writer);
        $updates = array_map(function (Contract\Path $source): ?array {
            // TARGET should exist.
            $target = $writer[$source->path()] ?? throw new \Exception;

            // Only if they're different.
            if ($source->isSame($target)) {
                return null;
            }

            return [$source, $target];
        }, $updates);
        $updates = array_filter($updates, fn (?array $current) => $current !== null);

        // Make sure everything is valid.
        $this->creates = $creates;
        $this->deletes = $deletes;
        $this->updates = $updates;
    }
}
