<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use TCB\FlysystemSync\Path\File;

trait FileTrait
{
    use ActionTrait;

    public function __construct(
        public readonly File $path
    ) {
    }

    private function readerExists(): bool
    {
        return $reader->fileExists($this->path->path);
    }

    private function writerExists(): bool
    {
        return $writer->fileExists($this->path->path);
    }
}
