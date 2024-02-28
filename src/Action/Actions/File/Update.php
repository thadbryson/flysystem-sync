<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\File;

use TCB\FlysystemSync\Action\Actions\AbstractAction;
use TCB\FlysystemSync\Action\Actions\Traits;

readonly class Update extends AbstractAction
{
    use Traits\Update;

    public function execute(): void
    {
        $path = $this->path->path();

        $this->writer->delete($path);
        $this->writer->writeStream(
            $path,
            $this->reader->readStream($path)
        );
    }

    public function isFile(): bool
    {
        return true;
    }
}
