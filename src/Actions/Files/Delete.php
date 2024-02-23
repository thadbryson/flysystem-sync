<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Files;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Paths\Directory;

class Delete
{
    public function __construct(
        protected Filesystems\Extended $reader,
        protected Filesystems\Extended $writer,
        protected Directory $target
    ) {
    }

    public function execute(): void
    {
        $this->writer->delete($this->target->path);
    }

    public function isSuccess(): bool
    {
        return $this->writer->fileExists($this->target->path);
    }
}
