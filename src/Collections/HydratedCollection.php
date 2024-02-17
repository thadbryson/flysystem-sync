<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\FilesystemReader;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Attributes\AttributesCompare;
use TCB\FlysystemSync\Exceptions;
use function array_diff_key;
use function array_filter;
use function array_intersect_key;
use function array_map;

readonly class HydratedCollection
{
    /**
     * @var StorageAttributes[]
     */
    public array $creates;

    /**
     * @var StorageAttributes[]
     */
    public array $deletes;

    /**
     * @var StorageAttributes[]
     */
    public array $updates;

    public function __construct(FilesystemReader $reader, FilesystemReader $writer, PathCollection $paths)
    {
        $reader = $paths->hydrate($reader);
        $writer = $paths->hydrate($writer);

        $this->creates = array_diff_key($reader, $writer);      // On SOURCES only
        $this->deletes = array_diff_key($writer, $reader);      // On TARGETs only

        // On BOTH and different
        // We only want pairs with different properties.
        $updates = array_intersect_key($reader, $writer);
        $updates = array_map(

            function (StorageAttributes $source) use ($writer): ?array {
                // TARGET should exist.
                $target = $writer[$source->path()] ??
                    throw new Exceptions\PathNotFound($source->path());

                // Only if they're different.
                if (AttributesCompare::isSame($source, $target) === false) {
                    return [
                        $source,
                        $target,
                    ];
                }

                return null;
            },

            $updates
        );

        // Remove the NULLs
        $this->updates = array_filter($updates, fn($value) => $value !== null);
    }
}
