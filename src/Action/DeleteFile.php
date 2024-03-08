<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;
use TCB\FlysystemSync\Path\File;

readonly class DeleteFile implements Contracts\DeleteFile
{
    public function __construct(
        public File $target
    ) {
    }

    public function __invoke(Reader $reader, Writer $writer): void
    {
        $writer->delete($this->target->path);
    }

    public function isSuccess(Writer $writer): bool
    {
        return $writer->fileExists($this->target->path) === false;
    }
}
