<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use TCB\FlysystemSync\Path\Directory;

interface CreateDirectory extends Action
{
    public function __construct(Directory $source);
}
