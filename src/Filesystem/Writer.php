<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Filesystem;

use League\Flysystem\Filesystem;
use TCB\FlysystemSync\Filesystem\Traits\LoaderTrait;

/**
 * Extended functions on top of Filesystem
 */
class Writer extends Filesystem
{
    use LoaderTrait;
}
