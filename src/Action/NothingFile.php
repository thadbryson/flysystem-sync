<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class NothingFile implements Contracts\NothingFile
{
    use IsSuccessTrait;

    public function __construct(
        public File $source,
        public File|Directory $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
    }
}
