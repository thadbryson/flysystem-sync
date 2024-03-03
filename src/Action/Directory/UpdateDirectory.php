<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use TCB\FlysystemSync\Action\Contracts\Directory;
use TCB\FlysystemSync\Action\Traits\Actions\UpdateTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;

readonly class UpdateDirectory implements Directory
{
    use DirectoryTrait,
        UpdateTrait;

    public function execute(): void
    {
        $this->writer->deleteDirectory($this->location);
        $this->writer->createDirectory($this->location);
    }
}
