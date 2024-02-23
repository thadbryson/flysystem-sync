<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contracts;

/**
 * @property-read string $path
 */
interface Path
{
    /**
     * Different somehow?
     * Only need to compare ->lastModified()
     *      ->fileSize() doesn't really matter to compare
     *
     * @throws \Exception - Must be the same PATH
     */
    public function isEqual(Path $compare): bool;
}
