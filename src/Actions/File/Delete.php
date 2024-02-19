<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Actions\File;

use TCB\FlysystemSync\Actions\Contract;
use TCB\FlysystemSync\Actions\Traits\ActionTrait;
use TCB\FlysystemSync\PathTypes;

class Delete implements Contract\File, Contract\Delete
{
    use ActionTrait;

    public const array ASSERT = [
        'source' => PathTypes::NON_EXISTING,
        'target' => PathTypes::FILE,
    ];

    public function execute(): void
    {
        $this->writer->delete($this->target);
    }
}
