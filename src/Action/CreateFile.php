<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\IsSuccessFileTrait;
use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\File;

readonly class CreateFile implements Contracts\CreateFile
{
    use IsSuccessFileTrait;

    public function __construct(
        public File $source
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->writeStream(
            $this->source->path,
            $reader->readStream($this->source->path)
        );
    }
}
