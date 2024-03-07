<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class NothingDirectory implements Contracts\NothingDirectory
{
    public function __construct(
        public Directory $source,
        public File|Directory $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
    }
}
