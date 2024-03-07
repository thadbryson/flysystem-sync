<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\Directory;
use TCB\FlysystemSync\Path\File;

readonly class UpdateFile implements Action
{
    public array $differences;

    public function __construct(
        public File $source,
        public File|Directory $target,
    ) {
        $this->differences = $this->source->getDifferences($this->target);
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->writeStream(
            $this->target->path,
            $reader->readStream($this->source->path)
        );
    }
}
