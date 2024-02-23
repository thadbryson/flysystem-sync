<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directories;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Paths\Directory;

readonly class Create
{
    public function __construct(
        protected Filesystems\Extended $reader,
        protected Filesystems\Extended $writer,
        protected Directory $source
    ) {
    }

    public function execute(): void
    {
        $this->writer->createDirectory($this->source->path);
    }

    public function isSuccess(): bool
    {
        return $this->writer->directoryExists($this->source->path);
    }
}
