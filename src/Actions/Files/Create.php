<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Files;

use TCB\FlysystemSync\Filesystems;
use TCB\FlysystemSync\Paths\File;

readonly class Create
{
    public function __construct(
        protected Filesystems\Extended $reader,
        protected Filesystems\Extended $writer,
        protected File $source,
        protected File $target
    ) {
    }

    public function execute(): void
    {
        $this->writer->writeStream(
            $this->source->path,
            $this->reader->readStream($this->source->path)
        );
    }

    public function isSuccess(): bool
    {
        return $this->writer->fileExists($this->target->path);
    }
}
