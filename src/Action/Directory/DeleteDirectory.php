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

    public function execute(): static
    {
        $this->writer->deleteDirectory($this->directory->path());

        return $this;
    }

    public function isExpected(): bool
    {
        return $this->writer->directoryExists($this->path) === false;
    }
}
