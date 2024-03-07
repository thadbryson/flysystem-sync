<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use TCB\FlysystemSync\Path\File;

interface CreateFile extends Action
{
    public function __construct(File $source);
}
