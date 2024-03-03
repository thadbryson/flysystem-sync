<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use TCB\FlysystemSync\Action\Contracts\Directory;
use TCB\FlysystemSync\Action\Traits\Actions\DeleteTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;

readonly class DeleteDirectory implements Directory
{
    use DirectoryTrait,
        DeleteTrait;

    public function execute(): void
    {
        $this->writer->deleteDirectory($this->location);
    }
}
