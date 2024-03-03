<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Directory;

use TCB\FlysystemSync\Action\Contracts\Directory;
use TCB\FlysystemSync\Action\Traits\Actions\NothingTrait;
use TCB\FlysystemSync\Action\Traits\Types\DirectoryTrait;

readonly class NothingDirectory implements Directory
{
    use DirectoryTrait,
        NothingTrait;

    public function execute(): void
    {
    }
}
