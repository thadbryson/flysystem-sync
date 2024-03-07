<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\DeleteTrait;
use TCB\FlysystemSync\Path\Directory;

readonly class DeleteDirectory implements Contracts\DeleteDirectory
{
    use DeleteTrait;

    public function __construct(
        public Directory $target
    ) {
    }
}
