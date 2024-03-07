<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

/**
 * @property-read File|Directory $target
 */
trait DeleteTrait
{
    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->delete($this->target);
    }

    public function isSuccess(Writer $writer): bool
    {
        return $writer->exists($this->target) === false;
    }
}
