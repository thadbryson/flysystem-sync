<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;

readonly class CreateDirectory implements Contracts\CreateDirectory
{
    public function __construct(
        public Directory $source
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->createDirectory($this->source->path);
        $writer->setVisibility($this->source->path, $this->source->visibility);
    }
}
