<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directory;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\PathTypes;

class Delete implements Contract\Directory, Contract\Delete
{
    use ActionTrait;

    public const array ASSERT = [
        'source' => PathTypes::NON_EXISTING,
        'target' => PathTypes::DIRECTORY,
    ];

    public function execute(): void
    {
        $this->writer->deleteDirectory($this->target);
    }
}
