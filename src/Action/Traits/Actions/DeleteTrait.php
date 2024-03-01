<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Traits\Actions;

trait DeleteTrait
{
    public function isOnReader(): false
    {
        return false;
    }

    public function isOnWriter(): true
    {
        return true;
    }
}
