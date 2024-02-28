<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Traits;

trait Delete
{
    protected function isExistingReader(): bool
    {
        return false;
    }

    protected function isExistingWriterBefore(): bool
    {
        return true;
    }
}
