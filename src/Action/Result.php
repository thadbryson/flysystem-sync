<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action;

use TCB\FlysystemSync\Action\Actions\Contracts\Action;

class Result
{
    protected Action $action;

    public readonly bool $has_ran;

    public function __construct(Action $action, bool $has_ran)
    {
        $this->action  = $action;
        $this->has_ran = $has_ran;
    }

    public function isSuccess(): bool
    {
        return $this->action->isSuccess();
    }
}
