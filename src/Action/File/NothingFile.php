<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\File;

use TCB\FlysystemSync\Action\Contracts\File;
use TCB\FlysystemSync\Action\Traits\Actions\NothingTrait;
use TCB\FlysystemSync\Action\Traits\Types\FileTrait;

readonly class NothingFile implements File
{
    use FileTrait,
        NothingTrait;

    public function execute(): void
    {
    }
}
