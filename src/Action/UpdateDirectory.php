<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateDirectory implements Contracts\UpdateDirectory
{
    use IsSuccessTrait;

    public function __construct(
        public Directory $source,
        File|Directory $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        // Only thing to update on a directory is its visibility.
        $writer->setVisibility($this->source);
    }
}
