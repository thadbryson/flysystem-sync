<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Actions;

trait NothingTrait
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
