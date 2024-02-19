<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directory;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\Paths\Contract as PathContract;

class Create implements Contract\Create, PathContract\Directory
{
    use ActionTrait;

    public function execute(): void
    {
        $this->writer->createDirectory($this->target);
    }
}
