<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Paths\Contract;

interface Path
{
    public function path(): string;
}
