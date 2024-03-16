<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class InvalidFile extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct('Not a FILE: ' . $path);
    }
}
