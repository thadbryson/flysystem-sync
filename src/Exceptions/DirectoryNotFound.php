<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class DirectoryNotFound extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct('DIRECTORY not found: ' . $path);
    }
}
