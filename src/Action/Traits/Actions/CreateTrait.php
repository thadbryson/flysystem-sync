<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Actions;

trait CreateTrait
{
    public function isOnReader(): true
    {
        return true;
    }

    public function isOnWriter(): false
    {
        return false;
    }
}
