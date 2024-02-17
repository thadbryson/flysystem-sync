<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class PathNotFound extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct('Path not found: ' . $path);
    }
}
