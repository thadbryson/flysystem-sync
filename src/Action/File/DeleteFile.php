<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use TCB\FlysystemSync\Action\Contracts\File;
use TCB\FlysystemSync\Action\Traits\Actions\DeleteTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;

readonly class DeleteFile implements File
{
    use FileTrait,
        DeleteTrait;

    public function execute(): static
    {
        $this->writer->delete($this->file->path());

        return $this;
    }

    public function isExpected(): bool
    {
        return $this->writer->fileExists($this->path) === false;
    }
}
