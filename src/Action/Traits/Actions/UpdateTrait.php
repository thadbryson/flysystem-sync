<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Actions;

trait UpdateTrait
{
    public function isOnReader(): true
    {
        return true;
    }

    public function isOnWriter(): true
    {
        return true;
    }
}
