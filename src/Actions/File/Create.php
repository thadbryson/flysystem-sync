<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\File;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\Paths\Contract as PathContract;

class Create implements Contract\Create, PathContract\File
{
    use ActionTrait;

    public function execute(): void
    {
        $this->writer->writeStream(
            $this->target,
            $this->reader->readStream($this->source)
        );
    }
}
