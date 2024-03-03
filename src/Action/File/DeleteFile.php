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

    public function execute(): void
    {
        $this->writer->delete($this->location);
    }
}
