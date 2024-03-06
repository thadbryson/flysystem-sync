<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\Traits\LoaderTrait;

/**
 * Only Filesystem reading functions.
 *
 * No write functionality.
 */
class WriterFilesystem extends Filesystem
{
    use LoaderTrait;
}
