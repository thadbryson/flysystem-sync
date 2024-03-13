<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class FileNotFound extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct('FILE not found: ' . $path);
    }
}
