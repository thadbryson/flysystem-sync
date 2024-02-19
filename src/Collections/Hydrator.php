<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Collections;

use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Attributes;
use TCB\FlysystemSync\Enums\PathTypes;
use TCB\FlysystemSync\Exceptions;
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
     * @param ?StorageAttributes[] $reader - path string key => ?StorageAttributes
     * @param ?StorageAttributes[] $writer - path string key => ?StorageAttributes
     *
     * @throws Exceptions\PathNotFound
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
        $updates = array_map(function (StorageAttributes $source, string $path): ?array {
            $this->assertPath($path, $source);

            // TARGET should exist.
            $target = $writer[$source->path()] ?? throw new Exceptions\PathNotFound($source->path());

            // Only if they're different.
            if (Attributes\Compare::isSame($source, $target)) {
                return null;
            }

            return [$source, $target];
        }, $updates);
        $updates = array_filter($updates, fn (?array $current) => $current !== null);

        // Make sure everything is valid.
        $this->creates = $this->assertPathsAll($creates);
        $this->deletes = $this->assertPathsAll($deletes);
        $this->updates = $updates;
    }

    protected function assertPathsAll(array $collection): array
    {
        foreach ($collection as $path => $current) {
            $this->assertPath($path, $current);
        }

        return $collection;
    }

    protected function assertPath(string $path, ?StorageAttributes $attributes): void
    {
        PathTypes::assertPath($attributes);

        if ($path !== $attributes->path()) {
            throw new \Exception('PATHs do not match key/Attributes');
        }
    }
}
