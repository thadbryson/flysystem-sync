<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Types;

use TCB\FlysystemSync\Path\Directory;

trait DirectoryTrait
{
    use ActionTrait;

    public function __construct(
        public readonly Directory $path
    ) {
    }

    private function readerExists(): bool
    {
        return $reader->directoryExists($this->path->path);
    }

    private function writerExists(): bool
    {
        return $writer->directoryExists($this->path->path);
    }
}
