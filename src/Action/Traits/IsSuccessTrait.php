<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits;

use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

/**
 * @property-read File|Directory $source
 */
trait IsSuccessTrait
{
    public function isSuccess(Writer $writer): bool
    {
        return $writer->isSameLoaded($this->source);
    }
}
