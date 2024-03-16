<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class SourceNotFound extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct('SOURCE not found: ' . $path);
    }
}
