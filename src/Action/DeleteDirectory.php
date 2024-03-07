<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;

readonly class DeleteDirectory implements Contracts\DeleteDirectory
{
    public function __construct(
        public Directory $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->deleteDirectory($this->target->path);
    }
}
