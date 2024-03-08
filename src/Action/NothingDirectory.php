<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessDirectoryTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;

readonly class NothingDirectory implements Contracts\NothingDirectory
{
    use IsSuccessDirectoryTrait;

    public function __construct(
        public Directory $source,
        public Directory $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
    }
}
