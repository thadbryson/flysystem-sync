<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Exceptions;

use Exception;

class MethodNotAllowed extends Exception
{
    public function __construct(string $class, string $method)
    {
        $method = trim($method, '()');
        $method .= '()';

        parent::__construct(sprintf('Method not allowed on class %s - %s', $class, $method));
    }
}
