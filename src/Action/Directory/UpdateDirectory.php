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

    public function execute(): static
    {
        $this->writer->deleteDirectory($this->directory->path());
        $this->writer->createDirectory($this->directory->path());

        return $this;
    }
}
