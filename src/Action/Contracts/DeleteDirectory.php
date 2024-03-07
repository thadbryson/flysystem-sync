<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use TCB\FlysystemSync\Path\Directory;

interface DeleteDirectory extends Action
{
    public function __construct(Directory $target);
}
