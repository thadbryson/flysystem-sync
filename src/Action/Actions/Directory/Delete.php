<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Directory;

use TCB\FlysystemSync\Action\Actions\AbstractAction;
use TCB\FlysystemSync\Action\Actions\Traits;

readonly class Delete extends AbstractAction
{
    use Traits\Delete;

    public function execute(): void
    {
        $this->writer->deleteDirectory($this->path->path());
    }

    public function isFile(): bool
    {
        return false;
    }
}
