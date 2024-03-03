<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use TCB\FlysystemSync\Action\Contracts\File;
use TCB\FlysystemSync\Action\Traits\Actions\CreateTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;

readonly class CreateFile implements File
{
    use FileTrait,
        CreateTrait;

    public function execute(): void
    {
        $this->writer->writeStream(
            $this->location,
            $this->reader->readStream($this->location)
        );
    }
}
