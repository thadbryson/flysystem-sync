<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Helpers;

use League\Flysystem\WhitespacePathNormalizer;

use function trim;

class PathHelper
{
    public static function prepare(string $path): string
    {
        $path = trim($path);
        $path = trim($path, '/');

        return (new WhitespacePathNormalizer)->normalizePath($path);
    }
}
