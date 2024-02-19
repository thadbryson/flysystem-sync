<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\Directory;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\PathTypes;

class Update implements Contract\Directory, Contract\Update
{
    use ActionTrait;

    public const array ASSERT = [
        'source' => PathTypes::DIRECTORY,
        'target' => PathTypes::NON_EXISTING,
    ];

    public function execute(): void
    {
        $this->writer->deleteDirectory($this->target);
        $this->writer->createDirectory($this->target);
    }
}
