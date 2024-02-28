<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Directory;

use TCB\FlysystemSync\Action\Actions\AbstractAction;
use TCB\FlysystemSync\Action\Actions\Traits;

readonly class Create extends AbstractAction
{
    use Traits\Create;

    public function execute(): void
    {
        $this->writer->createDirectory($this->path->path());
    }

    public function isFile(): bool
    {
        return false;
    }
}
