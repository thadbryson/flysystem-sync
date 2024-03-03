<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use TCB\FlysystemSync\Action\Contracts\Directory;
use TCB\FlysystemSync\Action\Traits\Actions\CreateTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;

readonly class CreateDirectory implements Directory
{
    use DirectoryTrait,
        CreateTrait;

    public function execute(): void
    {
        $this->writer->createDirectory($this->path);
    }
}
