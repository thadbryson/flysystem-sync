<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Functions\Helper;

use League\Flysystem\WhitespacePathNormalizer;

use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

use function array_key_exists;
use function array_map;
use function trim;

function path_prepare(string $path): string
{
    $path = trim($path);
    $path = trim($path, '/');

    return (new WhitespacePathNormalizer)->normalizePath($path);
}

function path_prepare_many(string ...$paths): array
{
    return array_map(fn (string $current): string => path_prepare($current), $paths);
}

/**
 * NOTE: must pass in $path because $object could be NULL.
 *
 * @throws \Exception
 */
function path_collect(array &$bag, string $path, File|Directory|null $object): void
{
    if (array_key_exists($path, $bag)) {
        throw new \Exception('Already collected');
    }

    $bag[$path] = $object;
}
