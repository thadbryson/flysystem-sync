<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

/**
 * @property-read ReaderFilesystem reader
 * @property-read Filesystem       writer
 *
 * @property-read string           path
 */
trait ActionTrait
{
    public function getDifferences(): array
    {
        $source = HelperFilesystem::loadPath($this->reader, $this->path);
        $target = HelperFilesystem::loadPath($this->writer, $this->path);

        return HelperFilesystem::getDifferences($source, $target);
    }
}
