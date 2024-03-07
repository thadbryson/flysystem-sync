<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Contracts;

use TCB\FlysystemSync\Filesystem\Reader;
use TCB\FlysystemSync\Filesystem\Writer;

interface Action
{
    public function __invoke(Reader $reader, Writer $writer): void;
}
