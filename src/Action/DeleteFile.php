<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Traits\DeleteTrait;
use TCB\FlysystemSync\Path\File;

readonly class DeleteFile implements Contracts\DeleteFile
{
    use DeleteTrait;

    public function __construct(
        public File $target
    ) {
    }
}
