<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Action\Actions\Traits;

trait Create
{
    protected function isExistingReader(): bool
    {
        return true;
    }

    protected function isExistingWriterBefore(): bool
    {
        return false;
    }
}
